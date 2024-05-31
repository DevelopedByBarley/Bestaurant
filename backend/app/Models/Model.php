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



  // KERESÉS MEGVALÓSÍTÁSA
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
      var_dump($e);
      exit;
    }
  }


  public function paginate($results, $limit, $search, $searchCondition)
  {
    // Lapozáshoz szükséges változók inicializálása
    $offset = isset($_GET["offset"]) ? max(1, intval($_GET["offset"])) : 1;
    $calculated = ($offset - 1) * $limit;

    // A lekérdezett eredmények számának meghatározása
    $countOfRecords = count($results);
    $numOfPage = ceil($countOfRecords / $limit);

    // Lapozott eredmények kiválasztása a limit és offset alapján
    $pagedResults = array_slice($results, $calculated, $limit);

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

      echo "An error occurred during the database operation:" . $e->getMessage();
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
      return false;
    }
  }

  public function insert()
  {
  }

  public function join()
  {
  }
}
