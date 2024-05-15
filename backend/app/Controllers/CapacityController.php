<?php

namespace App\Controllers;

use App\Controllers\Controller;
use PDO;

class CapacityController extends Controller
{
  public function index() {

    $capacity = $this->Model->selectByRecord('capacities','is_default', 1, PDO::PARAM_INT);
    echo json_encode([
      'data' => $capacity
    ]);
  }
  

}
