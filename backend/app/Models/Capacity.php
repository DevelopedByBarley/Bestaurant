<?php

namespace App\Models;

use App\Models\Model;
use Exception;
use InvalidArgumentException;
use PDO;
use PDOException;

class Capacity extends Model
{
  public function setDefaultCapacityIfItEmpty()
  {
    try {
      $stmt = $this->Pdo->prepare("SELECT COUNT(*) as count FROM current_capacities");
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      $count =  $result['count'];
      $body = [
        'validFrom' => date('Y-d-m', time()),
        'capacity' => 10
      ];

      if ($count === 0) {
        self::storeDefaultCapacity($body);
        return;
      }
    } catch (PDOException $e) {
      throw new Exception("An error occurred during the database operation in the isCurrentCapacitiesTableEmpty method: " . $e->getMessage());
      return false;
    }
  }

  public function capacities()
  {
    try {
      $stmt = $this->Pdo->prepare("SELECT * FROM `capacities` WHERE `date` >= CURDATE() ORDER BY `date` ASC");
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
      $stmt = $this->Pdo->prepare("SELECT * FROM `default_capacities` WHERE `validFrom` <= CURDATE() ORDER BY `validFrom` DESC LIMIT 2");
      $stmt->execute();
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $latest = isset($result[0]) ? $result[0] : null;;

      if ($latest) {
        self::deleteExpiredCapacities($latest);
      }

      return $result;
    } catch (PDOException $e) {
      throw new Exception("An error occurred during the database operation in the getDefaultCapacity method: " . $e->getMessage());
    }
  }


  private function deleteExpiredCapacities($result)
  {

    try {
      $stmt = $this->Pdo->prepare("DELETE  FROM `default_capacities` WHERE `validFrom` < :lastValid");
      $stmt->bindParam(':lastValid', $result['validFrom'], PDO::PARAM_STR);
      $stmt->execute();
    } catch (PDOException $e) {
      throw new Exception("An error occurred during the database operation in the deleteExpiredCapacities method: " . $e->getMessage());
    }
  }


  public function storeDefaultCapacity($body)
  {
    try {
      $validFrom = isset($body['date']) ? filter_var($body['date'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
      $capacity = isset($body['capacity']) ? filter_var($body['capacity'], FILTER_SANITIZE_SPECIAL_CHARS) : '';


      $stmt = $this->Pdo->prepare("INSERT INTO `default_capacities` (`id`, `capacity`, `createdAt`, `validFrom`) VALUES (NULL, :capacity, CURDATE(), :validFrom);");

      $stmt->bindParam(':capacity', $capacity, PDO::PARAM_INT);
      $stmt->bindParam(':validFrom', $validFrom, PDO::PARAM_STR);

      $stmt->execute();

      return $this->Pdo->lastInsertId();
    } catch (PDOException $e) {
      throw new Exception("An error occurred during the database operation in the storeDefaultCapacity method: " . $e->getMessage());
    }
  }

  public function storeCapacityException($body) {
    try {
      $date = isset($body['date']) ? filter_var($body['date'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
      $capacity = isset($body['capacity']) ? filter_var($body['capacity'], FILTER_SANITIZE_SPECIAL_CHARS) : '';


      $stmt = $this->Pdo->prepare("INSERT INTO `capacities` (`id`, `date`, `capacity`, `created_at`) VALUES (NULL, :date, :capacity, current_timestamp());");

      $stmt->bindParam(':date', $date, PDO::PARAM_STR);
      $stmt->bindParam(':capacity', $capacity, PDO::PARAM_INT);

      $stmt->execute();

      return $this->Pdo->lastInsertId();
    } catch (PDOException $e) {
      throw new Exception("An error occurred during the database operation in the storeCapacityException method: " . $e->getMessage());
    }
  }

  public function updateNextDefaultCapacity($body, $id)
  {
    try {
      $validFrom = isset($body['date']) ? filter_var($body['date'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
      $capacity = isset($body['capacity']) ? filter_var($body['capacity'], FILTER_SANITIZE_SPECIAL_CHARS) : '';

      $stmt = $this->Pdo->prepare("UPDATE `default_capacities` SET `capacity` = :capacity, `validFrom` = :validFrom WHERE `default_capacities`.`id` = :id");

      $stmt->bindParam(':capacity', $capacity, PDO::PARAM_INT);
      $stmt->bindParam(':validFrom', $validFrom, PDO::PARAM_STR);
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);

      $stmt->execute();
    } catch (PDOException $e) {
      throw new Exception("An error occurred during the database operation in the updateNextDefaultCapacity method: " . $e->getMessage());
    }
  }
}
