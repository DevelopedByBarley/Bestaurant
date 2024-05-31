<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Capacity;
use PDO;

class CapacityController extends Controller
{
  private $Capacity;

  public function __construct()
  {
    $this->Capacity = new Capacity();
    parent::__construct();
  }




  public function index($vars)
  {

    $date = isset($vars['date']) ? filter_var($vars['date'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $exception = $this->Model->searchBySingleEntity('capacities', 'date', $date, '')[0]['capacity'] ?? null;
    $default = $this->Capacity->getDefaultCapacity()['capacity'];
    $current = !empty($exception) ? $exception : $default;

    if (!empty($current) && $current) {
      http_response_code(200);
      echo json_encode([
        'status' => true,
        'message' => $current['message'] ?? 'Fail',
        'dev' => $current['dev'] ?? 'Fail',
        'data' => $current
      ]);
    } else {
      http_response_code(500);
      echo json_encode([
        'status' => false,
        'message' => 'Szerver probléma , sikertelen kapacitás lekérése!',
        'dev' => 'Server problem, capacity fetch fail!',
        'data' => null
      ]);
    }
  }

  public function updateCapacity()
  {
  }
}
