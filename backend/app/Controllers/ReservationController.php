<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Capacity;
use App\Models\Reservation;

class ReservationController extends Controller
{
  private $Reservation;
  private $Capacity;

  public function __construct()
  {
    $this->Reservation = new Reservation();
    $this->Capacity = new Capacity();
    parent::__construct();
  }




  public function getReservations()
  {
    self::initializePOST();
    $date = isset($_POST['date']) ? filter_var($_POST['date'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $exception = $this->Model->searchBySingleEntity('capacities', 'date', $date, '')[0]['capacity'] ?? null;
    $default = $this->Capacity->getDefaultCapacity()['capacity'];
    $current = !empty($exception) ? $exception : $default;

    $reservations = $this->Reservation->reservations($_POST, $current);
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

    $headers = apache_request_headers();
    $auth = isset($headers['authorization']) ? $headers['authorization'] : null;
    $admin = null;

    if ($auth) {
      $accessToken = $this->Auth->getTokenFromHeaderOrSendErrorResponse();
      $admin = $this->Auth->decodeJwtOrSendErrorResponse($accessToken);
    }



    $reservation =  $this->Reservation->new($admin);
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
}
