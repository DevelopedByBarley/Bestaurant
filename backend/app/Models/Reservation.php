<?php

namespace App\Models;


use App\Models\Model;
use Exception;
use InvalidArgumentException;
use PDO;
use PDOException;

class Reservation extends Model
{




  public function getAllReservationsByMultipleQuery($date, $entity, $searched, $sort, $order)
  {
    // Alapértelmezett keresési feltétel beállítása
    $date = $date ?? '';
    $search = $searched ?? '';
    $entity = $entity ?? '';

    // Engedélyezett oszlopnevek és sorrendezési irányok
    $allowedEntities = ['id', 'start', 'numOfGuests', 'isAccepted', 'name', 'email', 'phone']; // Példa engedélyezett oszlopokra
    $allowedSortColumns = ['id', 'start', 'name', 'numOfGuests', 'isAccepted', 'date']; // Példa engedélyezett sorrendezési oszlopokra
    $allowedOrderDirections = ['ASC', 'DESC'];

    // Ellenőrizzük az entitás, a sorrendezési oszlop és az irány érvényességét
    if (($entity && !in_array($entity, $allowedEntities)) || ($sort && !in_array($sort, $allowedSortColumns)) || ($order && !in_array(strtoupper($order), $allowedOrderDirections))) {
      throw new InvalidArgumentException("Invalid query parameters .");
    }

    try {
      $datePattern = "%" . $date . "%";
      $searchedPattern = "%" . $search . "%";

      // Alap SQL lekérdezés
      $sql = "SELECT * FROM `reservations` WHERE `date` LIKE :date";

      // Dinamikusan hozzáadjuk a WHERE feltételeket ha vannak
      if ($entity) {
        $sql .= " AND `$entity` LIKE :searched";
      }

      // Dinamikusan hozzáadjuk a ORDER BY feltételeket ha vannak
      if ($sort && $order) {
        $sql .= " ORDER BY `$sort` $order";
      }

      $stmt = $this->Pdo->prepare($sql);
      $stmt->bindParam(":date", $datePattern, PDO::PARAM_STR);
      if ($entity) {
        $stmt->bindParam(":searched", $searchedPattern, PDO::PARAM_STR);
      }
      $stmt->execute();
      $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return $data;
    } catch (PDOException $e) {
      throw new Exception("An error occurred during the database operation in the getAllReservationsByMultipleQuery method: " . $e->getMessage());
    }
  }

  public function reservations($body, $capacity)
  {
    try {
      $date = $body['date'];
      $reservation_interval = (int)$body['interval'] * 60 * 60;
      $default_interval = 15 * 60; // Negyed óra másodpercben
      $num_of_quests = (int)$body['numOfGuests'];

      $holiday_date = $this->selectByRecord('holidays', 'date', $date, PDO::PARAM_STR);

      if ($holiday_date && $holiday_date['isHoliday']) {
        return [
          'status' => true,
          'message' => $holiday_date['description'],
          'isHoliday' => true,
          'dev' => 'Holiday!',
        ];
      } elseif ($holiday_date && !$holiday_date['isHoliday']) {
        $opening_hours = $holiday_date;
      } else {
        $opening_hours = self::getOpeningHoursByDate($date);
      }

      // Az új bontás alapján generáljuk az időintervallumokat
      $intervals = self::generateIntervalsByOpeningHours($date, $default_interval, $opening_hours, $reservation_interval, $capacity);

      $reservations = $this->selectAllByRecord('reservations', 'date', $date, PDO::PARAM_STR);

      $free_intervals = self::generateFreeIntervalsByCapacity($reservations, $intervals, $num_of_quests,);

      return [
        'status' => true,
        'dev' => 'Get free time intervals successfully!',
        'message' => $holiday_date && !$holiday_date['isHoliday'] ? $holiday_date['description'] : null,
        'data' => $free_intervals
      ];
    } catch (PDOException $e) {
      throw new Exception("An error occurred during the database operation in the reservations  method in ReservationModel: " . $e->getMessage());
    }
  }

  public function new($admin)
  {

    try {
      $date = isset($_POST['date']) ? filter_var($_POST['date'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
      $start = isset($_POST['start']) ? filter_var($_POST['start'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
      $end = isset($_POST['end']) ? filter_var($_POST['end'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
      $numOfGuests = isset($_POST['numOfGuests']) ? filter_var($_POST['numOfGuests'], FILTER_VALIDATE_INT) : 0;
      $interval = isset($_POST['interval']) ? filter_var($_POST['interval'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
      $name = isset($_POST['name']) ? filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
      $phone = isset($_POST['phone']) ? filter_var($_POST['phone'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
      $request = isset($_POST['request']) ? filter_var($_POST['request'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
      $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';

      $isAccepted = $admin ? 1 : 0;

      $stmt = $this->Pdo->prepare("INSERT INTO `reservations`
          VALUES (NULL, :date, :start, :end, :numOfGuests, :intervalValue, :name, :phone, :request, :email, :isAccepted, :admin, current_timestamp())");

      $stmt->bindParam(':date', $date, PDO::PARAM_STR);
      $stmt->bindParam(':start', $start, PDO::PARAM_STR);
      $stmt->bindParam(':end', $end, PDO::PARAM_STR);
      $stmt->bindParam(':numOfGuests', $numOfGuests, PDO::PARAM_INT);
      $stmt->bindParam(':intervalValue', $interval, PDO::PARAM_INT);
      $stmt->bindParam(':name', $name, PDO::PARAM_STR);
      $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
      $stmt->bindParam(':request', $request, PDO::PARAM_STR);
      $stmt->bindParam(':email', $email, PDO::PARAM_STR);
      $stmt->bindParam(':isAccepted', $isAccepted, PDO::PARAM_INT);
      $stmt->bindParam(':admin', $admin['sub'], PDO::PARAM_INT);

      $stmt->execute();
    } catch (PDOException $e) {
      throw new Exception("An error occurred during the database operation in the new  method in ReservationModel: " . $e->getMessage());
    }
  }
  public function accept($reservationId, $admin)
  {

    try {
      $reservation = $this->selectByRecord('reservations', 'id', $reservationId, PDO::PARAM_INT);
      $stmt = $this->Pdo->prepare("UPDATE `reservations` SET `isAccepted` = '1', `admin` = :admin WHERE `id` = :reservationId");
      $stmt->bindParam(':admin', $admin['sub'], PDO::PARAM_INT);
      $stmt->bindParam(':reservationId', $reservationId, PDO::PARAM_INT);
      $isAccepted = $stmt->execute();


      if ($isAccepted) {

        $this->Mailer->renderAndSend(
          'AcceptReservation',
          [
            'name' => $reservation['name'],
            'date' => $reservation['date'],
            'start' => $reservation['start'],
            'end' => $reservation['end']
          ],
          $reservation['email'],
          'Foglalás elfogadva'
        );

        return $admin['sub'];
      }
    } catch (PDOException $e) {
      throw new Exception("An error occurred during the database operation in the accept method in Reservation Model: " . $e->getMessage());
    }
  }

  public function cancel($body, $reservationId)
  {
    $message = isset($body['message']) ? filter_var($body['message'], FILTER_SANITIZE_SPECIAL_CHARS) : '';

    try {
      $reservation = $this->selectByRecord('reservations', 'id', $reservationId, PDO::PARAM_INT);

      $stmt = $this->Pdo->prepare("DELETE FROM reservations WHERE `reservations`.`id` = :reservationId");
      $stmt->bindParam(":reservationId", $reservationId, PDO::PARAM_STR);
      $isAccepted = $stmt->execute();



      if ($isAccepted) {
        $this->Mailer->renderAndSend(
          'CancelReservation',
          [
            'name' => $reservation['name'],
            'message' => $message,
            'date' => $reservation['date'],
            'start' => $reservation['start'],
            'end' => $reservation['end']
          ],
          $reservation['email'],
          'Foglalás lemondva'
        );

        return $reservationId;
      }
    } catch (PDOException $e) {
      throw new Exception("An error occurred during the database operation in the cancel method in ReservationModel: " . $e->getMessage());
    }
  }

  public function delete($reservationId)
  {
    try {
      $stmt = $this->Pdo->prepare("DELETE FROM reservations WHERE `reservations`.`id` = :reservationId");
      $stmt->bindParam(":reservationId", $reservationId, PDO::PARAM_STR);
      $isAccepted = $stmt->execute();


      if ($isAccepted) {
        return  $reservationId;
      }
    } catch (PDOException $e) {
      throw new Exception("An error occurred during the database operation in the delete method in ReservationModel: " . $e->getMessage());
    }
  }


  private function generateIntervalsByOpeningHours($date, $default_interval, $dayOfTheWeek, $reservation_interval, $capacity)
  {

    $time_intervals = [];
    $openTime = strtotime($date . " " . $dayOfTheWeek["open"]);
    $closeTime = strtotime($date . " " . $dayOfTheWeek["close"]);

    $currentTime = $openTime;

    while ($currentTime < $closeTime - $reservation_interval) {
      // Hozzáadjuk a $reservation_interval-t a $default_interval-hoz
      $endTime = $currentTime + $reservation_interval;
      $time_intervals[] = [
        "date" => $date,
        "from" => date('H:i', $currentTime),
        "to" => date('H:i', $endTime),
        "capacity" => $capacity
      ];

      $currentTime += $default_interval;
    }

    return $time_intervals;
  }

  private function getOpeningHoursByDate($date)
  {
    try {
      $day = date("l", strtotime($date));
      $opening_hours = $this->selectByRecord('opening_hours', 'day', $day, PDO::PARAM_STR);

      return $opening_hours;
    } catch (Exception $e) {
      throw new Exception("An error occurred during the database operation in the getOpeningHours method: " . $e->getMessage());
    }
  }



  private function generateFreeIntervalsByCapacity($reservations, $time_intervals, $numOfGuests)
  {
    $free_intervals = [];
    /*    echo json_encode($reservations);
    echo '-------------------';
    echo json_encode($time_intervals);
    exit; */


    foreach ($reservations as $reservation) {
      foreach ($time_intervals as $index => $interval) {
        if (
          $reservation["start"] >= $interval["from"] && $reservation["start"] < $interval["to"] ||
          $reservation["end"] > $interval["from"] && $reservation["end"] <= $interval["to"] ||
          $interval["from"] >= $reservation["start"] && $interval["to"] <=  $reservation["end"]
        ) {
          $time_intervals[$index]["capacity"] -=  (int)$reservation["numOfGuests"];
        }
      }
    }

    foreach ($time_intervals as $interval) {
      if ($interval["capacity"] >= $numOfGuests) {
        $free_intervals[] = [
          "from" => $interval['from'],
          "to" => $interval['to'],
          "isReserved" => false
        ];
      } else {
        $free_intervals[] = [
          "from" => $interval['from'],
          "to" => $interval['to'],
          "isReserved" => true
        ];
      }
    }


    return $free_intervals;
  }
}
