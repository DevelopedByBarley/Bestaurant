<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Capacity;
use Exception;

class CapacityController extends Controller
{
  private $Capacity;

  public function __construct()
  {
    $this->Capacity = new Capacity();
    parent::__construct();
  }


  public function store()
  {
    self::initializePOST();

    try {
      $this->CSFRToken->check();
      $accessToken = $this->Auth->getTokenFromHeaderOrSendErrorResponse();
      $this->Auth->decodeJwtOrSendErrorResponse($accessToken);
      $id = $this->Capacity->storeCapacityException($_POST);

      $capacity = [
        "id" => $id,
        "date" => $_POST['date'],
        "capacity" => $_POST['capacity'],
        "created_at" => date('Y-m-d', time())
      ];

      http_response_code(200);
      echo json_encode([
        'status' => true,
        'exception' => $capacity
      ]);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'status' => false,
        'dev' => $e->getMessage()
      ]);
    }
  }

  public function updateNextDefault($vars)
  {
    self::initializePOST();
    $id = $vars['id'] ?? null;
    
    try {
      $accessToken = $this->Auth->getTokenFromHeaderOrSendErrorResponse();
      $this->Auth->decodeJwtOrSendErrorResponse($accessToken);
      $this->Capacity->updateNextDefaultCapacity($_POST, $id);
      $updated = $this->Model->show('default_capacities', $id);
      http_response_code(200);
      echo json_encode([
        'status' => true,
        'updated' => $updated
      ]);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'status' => false,
        'dev' => $e->getMessage()
      ]);
    }
  }

  public function storeDefault()
  {
    self::initializePOST();
    try {
      $accessToken = $this->Auth->getTokenFromHeaderOrSendErrorResponse();
      $this->Auth->decodeJwtOrSendErrorResponse($accessToken);
      $lastInsertedId = $this->Capacity->storeDefaultCapacity($_POST);
      http_response_code(200);
      echo json_encode([
        'status' => true,
        'lastInsertedId' => $lastInsertedId
      ]);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'status' => false,
        'dev' => $e->getMessage()
      ]);
    }
  }

  public function getAllCapacities()
  {
    $accessToken = $this->Auth->getTokenFromHeaderOrSendErrorResponse();
    $this->Auth->decodeJwtOrSendErrorResponse($accessToken);

    $sort = filter_input(INPUT_GET, 'sort', FILTER_SANITIZE_SPECIAL_CHARS);
    $order = filter_input(INPUT_GET, 'order', FILTER_SANITIZE_SPECIAL_CHARS);
    $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS) ?? '';


    $sort = $sort ?? '';
    $order = $order ?? '';
    try {
      $defaultCapacities = $this->Capacity->getDefaultCapacity();
      $results = $this->Capacity->getAllCapacitiesByMultipleQuery('date', $search, $sort, $order);
      $exceptionsOfCapacity = $this->Model->paginate($results, 10);

      http_response_code(200);
      echo json_encode([
        "defaultCapacity" => $defaultCapacities[0] ?? null,
        "nextDefaultCapacity" => $defaultCapacities[1] ?? null,
        "exceptions" => $exceptionsOfCapacity
      ]);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'status' => false,
        'dev' => $e->getMessage()
      ]);
    }
  }

  public function destroy($vars)
  {
    self::initializePOST();
    $accessToken = $this->Auth->getTokenFromHeaderOrSendErrorResponse();
    $this->Auth->decodeJwtOrSendErrorResponse($accessToken);

    $this->CSFRToken->check();



    $id = (int)$vars['id'];

    // Validate and sanitize the ID
    if (filter_var($id, FILTER_VALIDATE_INT) === false) {
      http_response_code(400); // Bad Request
      echo json_encode(['error' => 'Invalid ID provided']);
      exit;
    }


    try {
      $this->Model->deleteRecordById('capacities', $id);

      http_response_code(200);
      echo json_encode([
        'status' => true,
        'deletedId' => $id
      ]);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'status' => false,
        'dev' => $e->getMessage()
      ]);
      exit;
    }
  }

  public function updateExceptionCapacity($vars)
  {
    self::initializePOST();
    $this->CSFRToken->check();
    $accessToken = $this->Auth->getTokenFromHeaderOrSendErrorResponse();
    $this->Auth->decodeJwtOrSendErrorResponse($accessToken);

    $id = (int)$vars['id'];

    // Validate and sanitize the ID
    if (filter_var($id, FILTER_VALIDATE_INT) === false) {
      http_response_code(400); // Bad Request
      echo json_encode(['error' => 'Invalid ID provided']);
      exit;
    }

    try {
      $updatedCapacity = $this->Capacity->updateExceptionCapacity($_POST, $id);

      http_response_code(200);
      echo json_encode([
        'status' => true,
        'updated' => $updatedCapacity
      ]);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'status' => false,
        'dev' => $e->getMessage()
      ]);
      exit;
    }
  }



  public function index($vars)
  {
    try {
      $date = isset($vars['date']) ? filter_var($vars['date'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
      $exception = $this->Model->searchBySingleEntity('capacities', 'date', $date, '');
      $default = $this->Capacity->getDefaultCapacity()[0]; // 0 index is the latest default capacity!!

      $current = !empty($exception) ? $exception[0]['capacity'] : ($default ? $default['capacity'] : null);

      if ($current !== null) {
        http_response_code(200);
        echo json_encode([
          'status' => true,
          'message' => 'Kapacitás lekérése sikeres!',
          'dev' => 'Capacity fetched succsefully!',
          'data' => $current
        ]);
      }
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'status' => false,
        'message' => 'Szerver probléma, hiba történt a feldolgozás során!',
        'dev' => $e->getMessage(),
        'data' => null
      ]);
    }
  }
}
