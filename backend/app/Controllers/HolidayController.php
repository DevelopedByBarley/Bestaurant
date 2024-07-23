<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Holiday;
use Exception;
use PDO;

class HolidayController extends Controller
{

  private $Holiday;
  public function __construct()
  {
    $this->Holiday = new Holiday();
    parent::__construct();
  }

  public function getHolidays()
  {
    try {
      $results = $this->Model->all('holidays');
      $holidays = $this->Model->paginate($results, 10, '', null);
      echo json_encode([
        'status' => true,
        'holidays' => $holidays
      ]);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'status' => false,
        'dev' => $e->getMessage()
      ]);
    }
  }

  public function storeHoliday()
  {
    try {
      $this->initializePOST();
      $this->Holiday->store($_POST);
      echo json_encode([
        'status' => true,
        'holiday' => ''
      ]);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'status' => false,
        'dev' => $e->getMessage()
      ]);
    }
  }
}
