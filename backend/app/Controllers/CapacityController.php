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




  public function index()
  {
    $date = date('Y-m-d', time());

    $defaultCapacity = $this->Capacity->getDefaultCapacity();
    $capacityException = $this->Model->selectByRecord('capacities', 'date', $date, PDO::PARAM_STR);

    echo json_encode([
      'data' => $capacityException ? $capacityException : $defaultCapacity
    ]);
  }

  public function updateCapacity() {
    
  }
}
