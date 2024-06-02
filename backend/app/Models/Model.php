<?php

namespace App\Models;

use App\Helpers\Debug;
use App\Helpers\FileSaver;
use App\Helpers\Mailer;
use Exception;
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


  public function searchBySingleEntity($table, $entity, $searched, $searchDefault)
  {
    $search = $searched ?? $searchDefault;
    try {
      $searched = "%" . $search . "%"; // $searched előállítása
      $stmt = $this->Pdo->prepare("SELECT * FROM `$table` WHERE `$entity` LIKE :searched");
      $stmt->bindParam(":searched", $searched);
      $stmt->execute();
      $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

      return $data;
    } catch (PDOException $e) {
      throw new Exception("An error occurred during the database operation in the searchBySingleEntity method: " . $e->getMessage());
    }
  }


  public function paginate($results, $limit, $search, $searchCondition)
  {
    if (empty($results)) {
      return [
        "status" => true,
        "pages" => [],
        "numOfPage" => 0,
        "limit" => 0
      ];
    }

    $offset = isset($_GET["offset"]) ? max(1, intval($_GET["offset"])) : 1;
    $calculated = ($offset - 1) * $limit;

    // A lekérdezett eredmények számának meghatározása
    $countOfRecords = count($results);
    if ($countOfRecords === 0) {
      return [
        "status" => false,
        "message" => "No results found for the given search criteria.",
        "pages" => [],
        "numOfPage" => 0,
        "limit" => $limit
      ];
    }

    $numOfPage = ceil($countOfRecords / $limit);

    // Lapozott eredmények kiválasztása a limit és offset alapján
    $pagedResults = array_slice($results, $calculated, $limit);
    if (empty($pagedResults)) {
      return [
        "status" => false,
        "message" => "No paginated results found for the given offset and limit.",
        "pages" => [],
        "numOfPage" => 0,
        "limit" => $limit
      ];
    }

    $searchCondition($offset, $numOfPage, $search);

    return [
      "status" => true,
      "pages" => $pagedResults,
      "numOfPage" => $numOfPage,
      "limit" => $limit
    ];
  }

  /*  private function searchCondition($search, $offset, $numOfPage, $today)
  {
  
  } */

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

      throw new Exception("An error occurred during the database operation in the all method: " . $e->getMessage());
      return false;
    }
  }


  public function selectByRecord($table, $column, $value, $param)
  {
    try {
      $stmt = $this->Pdo->prepare("SELECT * FROM {$table} WHERE {$column} = :value");
      $stmt->bindParam(':value', $value, $param);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);

      return $result;
    } catch (PDOException $e) {
      throw new Exception("An error occurred during the database operation in the selectByRecord method: " . $e->getMessage());
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
    } catch (PDOException $e) {
      throw new Exception("An error occurred during the database operation in the selectAllByRecord method: " . $e->getMessage());
    }
  }

  public function insert()
  {
  }

  public function join()
  {
  }
}
