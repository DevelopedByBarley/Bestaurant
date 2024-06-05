<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Capacity;
use App\Models\Reservation;
use Exception;

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
    try {
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

      $reservations = $this->Model->paginate($searchResult, 2);

      if (isset($searchResult) && $reservations['status'] === true) {
        http_response_code(200);
        echo json_encode([
          'status' => true,
          'data' => $reservations ?? null
        ]);
        return;
      } else {
        http_response_code(500);
        echo json_encode([
          'status' => false,
          'dev' => $reservations['message']
        ]);
        return;
      }
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'status' => false,
        'dev' => $e->getMessage()
      ]);
    }
  }

  public function getReservations()
  {
    try {
      self::initializePOST();
      $date = isset($_POST['date']) ? filter_var($_POST['date'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
      $exception = $this->Model->searchBySingleEntity('capacities', 'date', $date, '')[0]['capacity'] ?? null; // ALAPVETŐEN fetchAllon van azért kell a [0]
      $default = $this->Capacity->getDefaultCapacity()[0]['capacity']; // A [0] a latest 
      $current = !empty($exception) ? $exception : $default;

      $reservations = $this->Reservation->reservations($_POST, $current);
      $isSuccess = $reservations['status'];


      if ($isSuccess) {
        http_response_code(200);
        echo json_encode([
          'message' => $reservations['message'] ?? null,
          'dev' => $reservations['dev'] ?? 'Fail',
          'isHoliday' => $reservations['isHoliday'] ?? false,
          'data' => $reservations['data'] ?? null,
        ]);
      }
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'status' => false,
        'dev' => $e->getMessage(),
        'data' => null
      ]);
    }
  }

  public function store()
  {

    try {
      self::initializePOST();
      $this->CSFRToken->check();

      $headers = apache_request_headers();
      $auth = isset($headers['authorization']) ? $headers['authorization'] : null;
      $admin = null;

      if ($auth) {
        $accessToken = $this->Auth->getTokenFromHeaderOrSendErrorResponse();
        $admin = $this->Auth->decodeJwtOrSendErrorResponse($accessToken);
      }

      $this->Reservation->new($admin);


      http_response_code(200);
      echo json_encode([
        'status' => true,
        'message' => 'Foglalás leadva!',
        'dev' => 'Reservation created succesfully!',
        'data' => null
      ]);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'status' => false,
        'dev' => $e->getMessage(),
      ]);
    }
  }

  public function acceptReservation($vars)
  {

    try {
      self::initializePOST();
      $accessToken = $this->Auth->getTokenFromHeaderOrSendErrorResponse();
      $admin = $this->Auth->decodeJwtOrSendErrorResponse($accessToken);

      $id = $vars['id'];
      $acceptedReservationWithAdminId = $this->Reservation->accept($id, $admin);

      http_response_code(200);
      echo json_encode([
        'status' => true,
        'message' => 'Foglalás elfogadva!',
        'dev' => 'Reservation accepted successfully!',
        'data' => $acceptedReservationWithAdminId
      ]);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'status' => false,
        'dev' => $e->getMessage(),
      ]);
    }
  }



  public function cancelReservation($vars)
  {

    try {
      self::initializePOST();
      $this->CSFRToken->check();
      $accessToken = $this->Auth->getTokenFromHeaderOrSendErrorResponse();
      $this->Auth->decodeJwtOrSendErrorResponse($accessToken);

      $id = $vars['id'];

      $canceled = $this->Reservation->cancel($_POST, $id);

      http_response_code(200);
      echo json_encode([
        'status' => true,
        'data' => $canceled ?? null
      ]);
    } catch (\Throwable $th) {
      http_response_code(500);
      echo json_encode([
        'status' => false,
        'dev' => $canceled['dev'] ?? null,
      ]);
    }
  }



  public function deleteReservation($vars)
  {

    try {
      self::initializePOST();
      $this->CSFRToken->check();
      $accessToken = $this->Auth->getTokenFromHeaderOrSendErrorResponse();
      $this->Auth->decodeJwtOrSendErrorResponse($accessToken);

      $id = $vars['id'];

      $deleted = $this->Reservation->delete($id);

      http_response_code(200);
      echo json_encode([
        'status' => true,
        'data' => $deleted ?? null
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
