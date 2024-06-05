<?php

namespace App\Models;

use App\Models\Model;
use Exception;
use InvalidArgumentException;
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

  public function getAllCapacitiesByMultipleQuery($entity, $searched, $sort, $order)
  {
    $search = $searched ?? '';
    $entity = $entity ?? '';

    // Engedélyezett oszlopnevek és sorrendezési irányok
    $allowedEntities = ['id', 'capacity', 'date', 'created_at']; // Példa engedélyezett oszlopokra
    $allowedSortColumns = ['id', 'capacity', 'date', 'created_at']; // Példa engedélyezett sorrendezési oszlopokra
    $allowedOrderDirections = ['ASC', 'DESC'];

    // Ellenőrizzük az entitás, a sorrendezési oszlop és az irány érvényességét
    if (($entity && !in_array($entity, $allowedEntities)) || ($sort && !in_array($sort, $allowedSortColumns)) || ($order && !in_array(strtoupper($order), $allowedOrderDirections))) {
      throw new InvalidArgumentException("Invalid query parameters.");
    }

    try {
      // Alap SQL lekérdezés
      $sql = "SELECT * FROM `capacities` WHERE `date` >= CURDATE()";

      // Dinamikusan hozzáadjuk a WHERE feltételeket ha van keresés
      if ($search !== '') {
        $sql .= " AND `date` LIKE :searched";
      }

      if ($sort && $order) {
        $sql .= " ORDER BY `$sort` $order";
      }

      $stmt = $this->Pdo->prepare($sql);

      if ($search !== '') {
        $searchedPattern = "%" . $search . "%";
        $stmt->bindParam(":searched", $searchedPattern, PDO::PARAM_STR);
      }

      $stmt->execute();
      $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return $data;
    } catch (PDOException $e) {
      throw new Exception("An error occurred during the database operation: " . $e->getMessage());
    }
  }



  public function updateExceptionCapacity($body, $id)
  {

    $date = isset($body['date']) ? filter_var($body['date'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $capacity = isset($body['capacity']) ? filter_var($body['capacity'], FILTER_SANITIZE_SPECIAL_CHARS) : '';


    try {
      $stmt = $this->Pdo->prepare("UPDATE `capacities` SET `date` = :date, `capacity` = :capacity WHERE `id` = :id");
      $stmt->bindParam(':date', $date, PDO::PARAM_STR);
      $stmt->bindParam(':capacity', $capacity, PDO::PARAM_INT);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->execute();

      $stmt = $this->Pdo->prepare("SELECT * FROM `capacities` WHERE `id` = :id");
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      $updatedRecord = $stmt->fetch(PDO::FETCH_ASSOC);

      return $updatedRecord;
    } catch (PDOException $e) {
      throw new Exception("Hiba történt az adatbázis művelet során az updateCapacity metódusban: " . $e->getMessage());
    }
  }




  public function getDefaultCapacity()
  {
    try {
      $stmt = $this->Pdo->prepare("SELECT * FROM `default_capacities` WHERE `validFrom` >= CURDATE() ORDER BY `validFrom` ASC LIMIT 2");
      $stmt->execute();
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      self::deleteExpiredCapacities($result);

      return $result;
    } catch (PDOException $e) {
      throw new Exception("An error occurred during the database operation in the getDefaultCapacity method: " . $e->getMessage());
    }
  }


  private function deleteExpiredCapacities($result)
  {
    try {
      $stmt = $this->Pdo->prepare("DELETE  FROM `default_capacities` WHERE `validFrom` < :lastValid ORDER BY `validFrom` DESC LIMIT 1");
      $stmt->bindParam(':lastValid', $result['validFrom'], PDO::PARAM_STR);
      $stmt->execute();
    } catch (PDOException $e) {
      throw new Exception("An error occurred during the database operation in the deleteExpiredCapacities method: " . $e->getMessage());
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
