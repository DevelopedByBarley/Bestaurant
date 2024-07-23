<?php

namespace App\Models;


use App\Models\Model;
use Exception;
use InvalidArgumentException;
use PDO;
use PDOException;

class OpeningHour extends Model
{

    /*     public function getOpeningHours()
    {
        $now = date('Y-m-d');
        
      try {
        $stmt = $this->Pdo->prepare("SELECT * FROM `opening_hours` WHERE `valid_from` <= :now ORDER BY `day` ASC");
        $stmt->bindParam(':now', $now, PDO::PARAM_STR);
        $stmt->execute();
        $opening_hours = $stmt->fetchAll(PDO::FETCH_ASSOC);
  
        return $opening_hours;
      } catch (PDOException $e) {
        throw new Exception("An error occurred during the database operation in the selectRecordByEntitySmallerOrEqual method: " . $e->getMessage());
      }
    } */

    public function updateOpeningHour($id, $body)
    {
        try {
            $open =  isset($body['open']) ? filter_var($body['open'], FILTER_SANITIZE_SPECIAL_CHARS) : '';;
            $close =  isset($body['close']) ? filter_var($body['close'], FILTER_SANITIZE_SPECIAL_CHARS) : '';;

            $stmt = $this->Pdo->prepare("UPDATE `opening_hours` SET `open` = :open, `close` = :close WHERE `opening_hours`.`id` = :id");
            $stmt->bindParam(':open', $open, PDO::PARAM_STR);
            $stmt->bindParam(':close', $close, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("An error occurred during the database operation in the updateOpeningHour method: " . $e->getMessage());
        }
    }
}
