<?php

namespace App\Models;


use App\Models\Model;
use PDO;
use PDOException;

class Reservation extends Model
{

  public function new()
  {
    try {
      $date = isset($_POST['date']) ? filter_var($_POST['date'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
      $start = isset($_POST['start']) ? filter_var($_POST['start'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
      $end = isset($_POST['end']) ? filter_var($_POST['end'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
      $numOfGuests = isset($_POST['numOfGuests']) ? filter_var($_POST['numOfGuests'], FILTER_VALIDATE_INT) : 0;
      $interval = isset($_POST['interval']) ? filter_var($_POST['interval'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
      $firstName = isset($_POST['firstName']) ? filter_var($_POST['firstName'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
      $lastName = isset($_POST['lastName']) ? filter_var($_POST['lastName'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
      $phone = isset($_POST['phone']) ? filter_var($_POST['phone'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
      $request = isset($_POST['request']) ? filter_var($_POST['request'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
      $email = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
      $isAccepted = 0;

      $stmt = $this->Pdo->prepare("INSERT INTO `reservations` (`id`, `date`, `start`, `end`, `numOfGuests`, `intervalValue`, `firstName`, `lastName`, `phone`, `request`, `email`, `isAccepted`, `createdAt`) 
          VALUES (NULL, :date, :start, :end, :numOfGuests, :intervalValue, :firstName, :lastName, :phone, :request, :email, :isAccepted, current_timestamp())");

      $stmt->bindParam(':date', $date, PDO::PARAM_STR);
      $stmt->bindParam(':start', $start, PDO::PARAM_STR);
      $stmt->bindParam(':end', $end, PDO::PARAM_STR);
      $stmt->bindParam(':numOfGuests', $numOfGuests, PDO::PARAM_INT);
      $stmt->bindParam(':intervalValue', $interval, PDO::PARAM_INT);
      $stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR);
      $stmt->bindParam(':lastName', $lastName, PDO::PARAM_STR);
      $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
      $stmt->bindParam(':request', $request, PDO::PARAM_STR);
      $stmt->bindParam(':email', $email, PDO::PARAM_STR);
      $stmt->bindParam(':isAccepted', $isAccepted, PDO::PARAM_INT);

      $isSuccess = $stmt->execute();
      return [
        'isSuccess' => $isSuccess,
        'message' => 'Időpont sikeresen lefoglalva!',
        'dev' => 'Reservation created succesfully!',
      ];
    } catch (PDOException $e) {
      return [
        'isSuccess' => false,
        'message' => 'Időpont foglalása sikertelen!',
        'dev' => $e->getMessage(),
      ];
    }
  }




  public function reservations($body)
  {
    try {
      $date = $body['date'];
      $reservation_interval = (int)$body['interval'] * 60 * 60;
      $default_interval = 15 * 60; // Negyed óra másodpercben
      $num_of_quests = (int)$body['numOfGuests'];

      $holiday_date = self::getDayIsHoliday($date);

      if ($holiday_date && $holiday_date['isHoliday']) {
        echo json_encode([
          'status' => false,
          'message' => $holiday_date['description'],
          'dev' => 'Holiday!',
        ]);
        exit;
      } elseif ($holiday_date && !$holiday_date['isHoliday']) {
        $opening_hours = $holiday_date;
      } else {
        $opening_hours = self::getOpeningHoursByDate($date);
      }

      // Az új bontás alapján generáljuk az időintervallumokat
      $intervals = self::generateIntervalsByOpeningHours($date, $default_interval, $opening_hours, $reservation_interval);

      $reservations = $this->selectAllByRecord('reservations', 'date', $date, PDO::PARAM_STR);

      $free_intervals = self::generateFreeIntervalsByCapacity($reservations, $intervals, $num_of_quests);

      return [
        'status' => true,
        'message' => 'Időpontok lekérése sikeres!',
        'dev' => 'Get free time intervals successfully!',
        'data' => $free_intervals
      ];
    } catch (PDOException $e) {
      // Adatbázis hiba kezelése
      echo json_encode([
        'status' => false,
        'message' => 'Adatbázis hiba történt',
        'dev' =>  $e->getMessage(),
      ]);
      exit;
    }
  }


  private function generateIntervalsByOpeningHours($date, $default_interval, $dayOfTheWeek, $reservation_interval)
  {
    $capacity = 10;
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



  private function getDayIsHoliday($date)
  {
    $isHoliday = $this->selectByRecord('holidays', 'date', $date, PDO::PARAM_STR);

    return $isHoliday;
  }


  private function getOpeningHoursByDate($date)
  {
    $day = date("l", strtotime($date));
    $opening_hours = $this->selectByRecord('opening_hours', 'day', $day, PDO::PARAM_STR);

    return $opening_hours;
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


  public function getAllReservationsWithoutAccept()
  {
    try {
      $stmt = $this->Pdo->prepare("SELECT * FROM `reservations` WHERE isAccepted = 0");
      $stmt->execute();
      $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return $results;
    } catch (PDOException  $e) {

      echo "An error occurred during the database operation:" . $e->getMessage();
      return false;
    }
  }
}
