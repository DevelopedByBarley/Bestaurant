<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Capacity;
use Exception;
use PDO;

class CapacityController extends Controller
{
  private $Capacity;

  public function __construct()
  {
    $this->Capacity = new Capacity();
    parent::__construct();
  }

  public function getAllCapacities()
  {
    try {
      $defaultCapacity = $this->Capacity->getDefaultCapacity();
      $results = $this->Capacity->capacities();
      $exceptionsOfCapacity = $this->Model->paginate($results, 10);

      http_response_code(200);
      echo json_encode([
        "defaultCapacity" => $defaultCapacity,
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




  public function index($vars)
  {
    try {
      $date = isset($vars['date']) ? filter_var($vars['date'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
      $exception = $this->Model->searchBySingleEntity('capacities', 'date', $date, '');
      $default = $this->Capacity->getDefaultCapacity();
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
