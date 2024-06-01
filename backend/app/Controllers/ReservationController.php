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


  public function index()
  {
    $accessToken = $this->Auth->getTokenFromHeaderOrSendErrorResponse();
    $this->Auth->decodeJwtOrSendErrorResponse($accessToken);

    $date = filter_input(INPUT_GET, 'date', FILTER_SANITIZE_SPECIAL_CHARS);
    $sort = filter_input(INPUT_GET, 'sort', FILTER_SANITIZE_SPECIAL_CHARS);
    $order = filter_input(INPUT_GET, 'order', FILTER_SANITIZE_SPECIAL_CHARS);
    $category = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_SPECIAL_CHARS);
    $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS);


    $sort = $sort ?? 'date';
    $order = $order ?? '';

    $searchResult = $this->Reservation->getAllReservationsByMultipleQuery($date, $category, $search, $sort, $order);

    $reservations = $this->Model->paginate(
      $searchResult,
      10,
      $date,
      function () {
      }
    );



    if (isset($searchResult) && $reservations['status'] === true) {
      http_response_code(200);
      echo json_encode([
        'status' => true,
        'message' => "Foglalások lekérése sikeres!" ?? null,
        'dev' => "Get reservations successfully!" ?? null,
        'data' => $reservations['pages'] ?? null
      ]);
      return;
    } else {
      http_response_code(500);
      echo json_encode([
        'status' => false,
        'message' => "Foglalások lekérése sikertelen!" ?? null,
        'dev' => "Get reservations problem!" ?? null,
        'data' => null
      ]);
    }
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

  public function acceptReservation($vars)
  {
    self::initializePOST();
    $accessToken = $this->Auth->getTokenFromHeaderOrSendErrorResponse();
    $admin = $this->Auth->decodeJwtOrSendErrorResponse($accessToken);

    $id = $vars['id'];
    $acceptedReservation = $this->Reservation->accept($id, $admin);
    $isSuccess = $acceptedReservation['status'];
    if ($isSuccess) {
      http_response_code(200);
      echo json_encode([
        'status' => true,
        'message' => $acceptedReservation['message'] ?? null,
        'dev' => $acceptedReservation['dev'] ?? null,
        'data' => $acceptedReservation['data']
      ]);
    } else {
      http_response_code(500);
      echo json_encode([
        'status' => false,
        'message' => $acceptedReservation['message'] ?? null,
        'dev' => $acceptedReservation['dev'] ?? null,
        'data' => null
      ]);
    }
  }



  public function cancelReservation($vars)
  {
    self::initializePOST();
    $this->CSFRToken->check();
    $accessToken = $this->Auth->getTokenFromHeaderOrSendErrorResponse();
    $this->Auth->decodeJwtOrSendErrorResponse($accessToken);

    $id = $vars['id'];

    $canceled = $this->Reservation->cancel($_POST, $id);

    if ($canceled) {
      http_response_code(200);
      echo json_encode([
        'status' => true,
        'message' => $canceled['message'] ?? null,
        'dev' => $canceled['dev'] ?? null,
        'data' => $canceled['data'] ?? null
      ]);
    } else {
      http_response_code(500);
      echo json_encode([
        'status' => false,
        'message' => $canceled['message'] ?? null,
        'dev' => $canceled['dev'] ?? null,
        'data' => null
      ]);
    }
  }
  public function deleteReservation($vars)
  {
    self::initializePOST();
    $this->CSFRToken->check();
    $accessToken = $this->Auth->getTokenFromHeaderOrSendErrorResponse();
    $this->Auth->decodeJwtOrSendErrorResponse($accessToken);

    $id = $vars['id'];

    $deleted = $this->Reservation->delete($id);

    if ($deleted) {
      http_response_code(200);
      echo json_encode([
        'status' => true,
        'message' => $deleted['message'] ?? null,
        'dev' => $deleted['dev'] ?? null,
        'data' => $deleted['data'] ?? null
      ]);
    } else {
      http_response_code(500);
      echo json_encode([
        'status' => false,
        'message' => $deleted['message'] ?? null,
        'dev' => $deleted['dev'] ?? null,
        'data' => null
      ]);
    }
  }
}
