<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Reservation;

class ReservationController extends Controller
{
  private $Reservation;

  public function __construct()
  {
    $this->Reservation = new Reservation();
    parent::__construct();
  }


  public function test() {
    $this->Reservation->reservations($_POST);
  }

  


}
