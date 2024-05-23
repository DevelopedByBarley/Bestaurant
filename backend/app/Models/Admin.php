<?php

namespace App\Models;

use App\Models\Model;
use PDO;
use PDOException;

class Admin extends Model
{
  public function storeAdmin($body)
  {
    try {
      $adminId = uniqid();
      $name = filter_var($body["name"] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
      $pw = password_hash(filter_var($body["password"] ?? '', FILTER_SANITIZE_SPECIAL_CHARS), PASSWORD_DEFAULT);

      $stmt = $this->Pdo->prepare("INSERT INTO `admins` (`id`, `adminId`, `name`, `password`, `created_at`) VALUES (NULL, :adminId, :name, :password, current_timestamp())");
      $stmt->bindParam(":adminId", $adminId, PDO::PARAM_STR);
      $stmt->bindParam(":name", $name, PDO::PARAM_STR);
      $stmt->bindParam(":password", $pw, PDO::PARAM_STR);
      $stmt->execute();
    } catch (PDOException $e) {
      echo "An error occurred during the database operation: " . $e->getMessage();
      exit;
    }
  }

  public function loginAdmin($body)
  {
    try {
      $name = filter_var($body["name"] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
      $pw = filter_var($body["password"] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);

      $stmt = $this->Pdo->prepare("SELECT * FROM `admins` WHERE `name` = :name");
      $stmt->bindParam(":name", $name, PDO::PARAM_STR);
      $stmt->execute();

      $admin = $stmt->fetch(PDO::FETCH_ASSOC);
      if (!$admin || !password_verify($pw, $admin["password"])) {
        return false;
      }

      
      return $admin;
    } catch (PDOException $e) {
      echo "An error occurred during the database operation: " . $e->getMessage();
      exit;
    }
  }


}
