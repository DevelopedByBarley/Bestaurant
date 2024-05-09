<?php

namespace App\Models;

use App\Models\Model;
use PDO;
use PDOException;

class User extends Model
{
  public function storeUser($body, $files)
  {
    $email = filter_var($body["email"] ?? '', FILTER_SANITIZE_EMAIL);
    $pw = password_hash(filter_var($body["password"] ?? '', FILTER_SANITIZE_SPECIAL_CHARS), PASSWORD_DEFAULT);
    $fileName = $this->FileSaver->saver($files['file'], 'uploads/images', null, ['image/png', 'image/jpeg']);


    $isUserExist = $this->selectByRecord('users', 'email', $email, PDO::PARAM_STR);

    if ($isUserExist) {
      return [
        "bg" => 'danger',
        "hu" => "Ezekkel az adatokkal sajnos nem lehet már regisztrálni, kérjük jelentkezzen be.",
        "en" => "Unfortunately, it is no longer possible to register with these data, please register.",
        'redirect' => '/user/register'
      ];
    }

    try {

      $stmt = $this->Pdo->prepare("INSERT INTO `users` (`id`, `email`, `password`,  `fileName`, `created_at`) VALUES (NULL, :email, :password, :fileName, current_timestamp())");
      $stmt->bindParam(":email", $email, PDO::PARAM_STR);
      $stmt->bindParam(":password", $pw, PDO::PARAM_STR);
      $stmt->bindParam(":fileName", $fileName, PDO::PARAM_STR);
      $stmt->execute();

      return [
        "bg" => 'success',
        "hu" => "Regisztráció sikeres!",
        "en" => "Registration successfully!",
        'redirect' => '/user/register'
      ];
    } catch (PDOException $e) {
      echo "An error occurred during the database operation: " . $e->getMessage();
      exit;
    }
  }

  public function loginUser($body)
  {
    try {

      $email = filter_var($body["email"] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
      $pw = filter_var($body["password"] ?? '', FILTER_SANITIZE_EMAIL);

     

      $stmt = $this->Pdo->prepare("SELECT * FROM `users` WHERE `email` = :email");
      $stmt->bindParam(":email", $email, PDO::PARAM_STR);
      $stmt->execute();

      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if (!$user || !password_verify($pw, $user["password"])) {
        return false;
      }

     
      return $user['id'];
    } catch (PDOException $e) {
      echo "An error occurred during the database operation: " . $e->getMessage();
      exit;
    }
  }
}
