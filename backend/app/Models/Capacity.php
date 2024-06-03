<?php

namespace App\Models;

use App\Models\Model;
use DateTime;
use Exception;
use PDO;
use PDOException;

class Capacity extends Model
{
  public function capacities()
  {
    try {
      $stmt = $this->Pdo->prepare("SELECT * FROM `capacities` WHERE `date` >= CURDATE() AND `is_default` = 0 ORDER BY `date` ASC");
      $stmt->execute();
      $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return $results;
    } catch (PDOException $e) {
      echo "An error occurred during the database operation capacities method in Model:" . $e->getMessage();
    }
  }



  public function getDefaultCapacity()
  {
    try {
      $stmt = $this->Pdo->prepare("SELECT * FROM `default_capacities` WHERE `validFrom` <= CURDATE() ORDER BY `validFrom` DESC LIMIT 1");
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result;
    } catch (PDOException $e) {
      throw new Exception("An error occurred during the database operation in the getDefaultCapacity method: " . $e->getMessage());
    }
  }


  public function addDefaultCapacity()
  {
    $currDate = date('Y-m-d');
    $validFrom = date('Y-m-d', strtotime($currDate . ' +1 day'));
    $capacity = filter_var($body["capacity"] ?? '', FILTER_SANITIZE_NUMBER_INT);

    $stmt = $this->Pdo->prepare("INSERT INTO `default_capacities` (`capacity`, `createdAt`, `validFrom`) VALUES (:capacity, :createdAt, :validFrom)");

    $stmt->bindParam(':capacity', $capacity, PDO::PARAM_INT);
    $stmt->bindParam(':createdAt', $currDate, PDO::PARAM_STR);
    $stmt->bindParam(':validFrom', $validFrom, PDO::PARAM_STR);

    $stmt->execute();
  }
}
