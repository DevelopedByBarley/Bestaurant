<?php

namespace App\Models;

use App\Models\Model;
use DateTime;
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
      echo "An error occurred during the database operation:" . $e->getMessage();
      return false;
    }
  }
}
