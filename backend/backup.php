<?php
/* 
namespace App\Models;

use App\Models\Model;
use DateTime;
use PDO;

class Reservation extends Model
{

  public function test($body)
  {
    $date = new DateTime($body['date']);
    $interval = (int)$body['interval'] * 60 * 60;
    $num_of_quests = (int)$body['num_of_quests'];

    $isHoliday = self::getDayIsHoliday($date);

    if (!$isHoliday) {
      return [
        'status' => false,
        'message' => "Ez szabadnak, a boltunk sajnos zárva tart"
      ];
    }

    $opening_hours = self::getOpeningHoursByDate($date);
    $intervals = self::generateIntervalsByOpeningHours($date->format('Y-m-d'), $interval, $opening_hours);
    $reservations = $this->selectAllByRecord('reservations', 'date', $date->format('Y-m-d'), PDO::PARAM_STR);


    $free_intervals = self::generateFreeIntervalsByCapacity($reservations, $intervals, $num_of_quests);
  
    var_dump($free_intervals);
  }


  private function getDayIsHoliday($date)
  {
    $date = $date->format('Y-m-d');
    $isHoliday = $this->selectByRecord('holidays', 'day', $date, PDO::PARAM_STR);

    return !empty($isHoliday);
  }


  private function getOpeningHoursByDate($date)
  {
    $day = date("l", strtotime($date->format('Y-m-d')));
    $opening_hours = $this->selectByRecord('opening_hours', 'day', $day, PDO::PARAM_STR);

    return $opening_hours;
  }


  private function generateIntervalsByOpeningHours($date, $interval, $dayOfTheWeek)
  {
    $capacity = 10;
    $time_intervals = [];
    $openTime = strtotime($date . " " . $dayOfTheWeek["open"]);
    $closeTime = strtotime($date . " " . $dayOfTheWeek["close"]);

    $currentTime = $openTime;

    while ($currentTime < $closeTime) {
      $time_intervals[] = [
        "date" => $date,
        "from" => date('H:i:s', $currentTime),
        "to" => date('H:i:s', $currentTime + $interval),
        "capacity" => $capacity
      ];

      $currentTime += $interval;
    }

    return $time_intervals;
  }

  private function generateFreeIntervalsByCapacity($reservations, $time_intervals, $numOfGuests)
  {
    $free_intervals = [];

    foreach ($reservations as $reservation) {
      foreach ($time_intervals as $index => $interval) {
        if (
          $reservation["start"] >= $interval["from"] && $reservation["start"] < $interval["to"] ||
          $reservation["end"] > $interval["from"] && $reservation["end"] <= $interval["to"]
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
}


 */