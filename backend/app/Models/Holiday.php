<?php

namespace App\Models;

use App\Models\Model;
use Exception;
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
  public function store($body)
  {

    try {
      // Szerezd meg a bemeneteket és ellenőrizd, hogy léteznek-e, valamint alkalmazz sanitizálást
      $date = isset($body['date']) ? filter_var($body['date'], FILTER_SANITIZE_STRING) : '';
      $open = isset($body['open']) ? filter_var($body['open'], FILTER_SANITIZE_STRING) : '';
      $close = isset($body['close']) ? filter_var($body['close'], FILTER_SANITIZE_STRING) : '';
      $description = isset($body['description']) ? filter_var($body['description'], FILTER_SANITIZE_STRING) : '';
      $isHoliday = isset($body['isHoliday']) ? filter_var($body['isHoliday'], FILTER_SANITIZE_NUMBER_INT) : 0; // Állítsd be alapértelmezett értéknek 0-t

      // Előkészítjük a SQL utasítást
      $stmt = $this->Pdo->prepare("INSERT INTO `holidays` (`id`, `date`, `isHoliday`, `open`, `close`, `description`, `created_at`) VALUES (NULL, :date, :isHoliday, :open, :close, :description, current_timestamp())");

      // Paraméterek kötése
      $stmt->bindParam(':date', $date, PDO::PARAM_STR);
      $stmt->bindParam(':open', $open, PDO::PARAM_STR);
      $stmt->bindParam(':close', $close, PDO::PARAM_STR);
      $stmt->bindParam(':isHoliday', $isHoliday, PDO::PARAM_INT); // Változó típusa: int
      $stmt->bindParam(':description', $description, PDO::PARAM_STR);

      // Utasítás végrehajtása
      $stmt->execute();

      return [
        'id' => $this->Pdo->lastInsertId(),
        'date' => $date,
        'open' => $open,
        'close' => $close,
        'description' => $description,
        'isHoliday' => $isHoliday,
        'created_at' => date('Y-m-d', time()) 
      ];
    } catch (PDOException $e) {
      // A kivételek kezelése
      throw new Exception("An error occurred during the database operation: " . $e->getMessage());
    }
  }
}
