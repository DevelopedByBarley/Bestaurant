<?php

namespace App\Models;

use App\Helpers\Debug;
use App\Helpers\FileSaver;
use App\Helpers\Mailer;
use PDO;
use PDOException;

class Model
{
  protected $Pdo;
  protected $Debug;
  protected $Mailer;
  protected $FileSaver;


  public function __construct()
  {
    DATABASE_PERM === 1 ? $this->Pdo = getConnect() : null;
    $this->Debug = new Debug();
    $this->Mailer = new Mailer();
    $this->FileSaver = new FileSaver();
  }

  public function sendMail()
  {
    $name = 'name';
    $this->Mailer->renderAndSend('Test', ['name' => $name], 'arpadsz@max.hu', 'Test');
  }

  public function test($table, $entity) {
    $stmt = $this->Pdo->prepare("SELECT * FROM `$table` WHERE `$entity` LIKE :searched");
    $stmt->bindParam(":searched", $searched);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return $data;
  }

  public function paginate($table, $entity, $limit, $searchDefault, $redirectCondition)
  {
    $offset = isset($_GET["offset"]) ? max(1, intval($_GET["offset"])) : 1;
    $calculated = ($offset - 1) * $limit; // Az OFFSET értékének kiszámítása
    $search = $_GET["search"] ?? $searchDefault;
    $searched = "%" . $search . "%";

    $stmt = $this->Pdo->prepare("SELECT COUNT(*) FROM `$table` WHERE `$entity` LIKE :searched");
    $stmt->bindParam(":searched", $searched);
    $stmt->execute();
    $countOfRecords = $stmt->fetch(PDO::FETCH_ASSOC)["COUNT(*)"];
    $numOfPage = ceil($countOfRecords / $limit); // A lapozó lapok számának kiszámítása


    $stmt = $this->Pdo->prepare("SELECT * FROM `$table` WHERE `$entity` LIKE :searched LIMIT $limit OFFSET $calculated");
    $stmt->bindParam(":searched", $searched);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    


    $redirectCondition($search, $offset, $numOfPage);

    return [
      "data" => $data,
      "numOfPage" => $numOfPage,
      "limit" => $limit
    ];
  }

  public function show($table, $id)
  {
    try {
      $stmt = $this->Pdo->prepare("SELECT * FROM `$table` WHERE id = :id");
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result;
    } catch (PDOException $e) {

      echo "An error occurred during the database operation:: " . $e->getMessage();
      return false;
    }
  }


  public function all($table)
  {
    try {
      $stmt = $this->Pdo->prepare("SELECT * FROM `$table`");
      $stmt->execute();
      $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return $results;
    } catch (PDOException  $e) {

      echo "An error occurred during the database operation:" . $e->getMessage();
      return false;
    }
  }

  public function selectByRecord($table, $entity, $value, $param, $condition = null)
  {

    try {
      $stmt = $this->Pdo->prepare("SELECT * FROM $table WHERE $entity = :entity");
      $stmt->bindParam(':entity', $value, $param);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result;
    } catch (\Throwable $th) {
      var_dump($th);
    }
  }
  
  public function selectAllByRecord($table, $entity, $value, $param)
  {
    try {
      $stmt = $this->Pdo->prepare("SELECT * FROM $table WHERE  $entity = :entity");
      $stmt->bindParam(':entity', $value, $param);
      $stmt->execute();
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return $result;
    } catch (\Throwable $th) {
      var_dump($th);
    }
  }

  public function insert()
  {
  }

  public function join()
  {
  }
}
