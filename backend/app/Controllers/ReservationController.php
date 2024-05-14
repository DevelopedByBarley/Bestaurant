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


  public function getReservations()
  {
    self::initializePOST();
    $reservations = $this->Reservation->reservations($_POST);
    $isSuccess = $reservations['status'];

    if ($isSuccess) {
      http_response_code(200);
      echo json_encode([
        'status' => true,
        'message' => $reservations['message'] ?? 'Fail',
        'dev' => $reservations['dev'] ?? 'Fail',
        'data' => $reservations['data']
      ]);
    } else {
      http_response_code(500);
      echo json_encode([
        'status' => false,
        'message' => $reservation['message'] ?? 'Fail',
        'dev' => $reservation['dev'] ?? 'Fail',
        'data' => null
      ]);
    }
  }

  public function store()
  {
    self::initializePOST();
    $this->CSFRToken->check();
    $reservation =  $this->Reservation->new();
    if ($reservation['isSuccess']) {
      http_response_code(200);
      echo json_encode([
        'status' => true,
        'message' => $reservation['message'] ?? 'Fail',
        'dev' => $reservation['dev'] ?? 'Fail',
        'data' => null
      ]);
    } else {
      http_response_code(500);
      echo json_encode([
        'status' => false,
        'message' => $reservation['message'] ?? 'Fail',
        'dev' => $reservation['dev'] ?? 'Fail',
        'data' => null
      ]);
    }
  }


  private function initializePOST()
  {
    $_POST = json_decode(file_get_contents('php://input'), true);
  }
}
