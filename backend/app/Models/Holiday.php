<?php

namespace App\Models;

use App\Models\Model;
use PDO;
use PDOException;

class Holiday extends Model
{
  public function holidays()
  {
    try {
      $stmt = $this->Pdo->prepare("SELECT * FROM `holidays` WHERE `date` >= CURDATE() ORDER BY `date` ASC");
      $stmt->execute();
      $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return $results;
    } catch (PDOException $e) {
      echo "An error occurred during the database operation:" . $e->getMessage();
      return false;
    }
  }
}
