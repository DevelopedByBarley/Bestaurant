<?php
namespace App\Models;


use App\Models\Model;
use DateTime;
use PDO;
use PDOException;

class Reservation extends Model
{




  public function reservations($body)
  {
      $date = $body['date'];
      $reservation_interval = (int)$body['interval'] * 60 * 60;
      $default_interval = 15 * 60; // Negyed óra másodpercben
      $num_of_quests = (int)$body['num_of_quests'];
  
      $holiday_date = self::getDayIsHoliday($date);
  
      if ($holiday_date['isHoliday']) {
          echo  $holiday_date['description'];
          return [
              'status' => false,
              'message' => $holiday_date['description']
          ];
      } elseif (!$holiday_date['isHoliday']) {
          $opening_hours = $holiday_date;
      } else {
          $opening_hours = self::getOpeningHoursByDate($date);
      }
  
      // Az új bontás alapján generáljuk az időintervallumokat
      $intervals = self::generateIntervalsByOpeningHours($date, $default_interval, $opening_hours, $reservation_interval);
  
      $reservations = $this->selectAllByRecord('reservations', 'date', $date, PDO::PARAM_STR);
      
      $free_intervals = self::generateFreeIntervalsByCapacity($reservations, $intervals, $num_of_quests);
  
       echo json_encode($free_intervals);
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
              "from" => date('H:i:s', $currentTime),
              "to" => date('H:i:s', $endTime),
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
        $free_intervals[] = $interval;
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

