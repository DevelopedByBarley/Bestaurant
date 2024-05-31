<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Admin;
use App\Models\Capacity;
use App\Models\Holiday;
use App\Models\Reservation;
use DateTime;
use PDO;

class AdminController extends Controller
{
  private $Admin;
  private $Reservation;
  private $Capacity;
  private $Holiday;

  public function __construct()
  {
    $this->Admin = new Admin();
    $this->Reservation = new Reservation();
    $this->Capacity = new Capacity();
    $this->Holiday = new Holiday();
    parent::__construct();
  }


  /*   public function loginPage()
  {
    session_start();

    $admin = $_SESSION["adminId"] ?? null;

    if ($admin) {
      header("Location: /admin/dashboard");
      exit;
    }


    echo $this->Render->write("admin/Layout.php", [
      "content" => $this->Render->write("admin/pages/Login.php", [
        "csfr" => $this->CSFRToken
      ])
    ]);
  }
 */

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



  function validateDate($date, $format = 'Y-m-d')
  {
    if ($date) {
      $d = DateTime::createFromFormat($format, $date);
      return $d && $d->format($format) === $date;
    }
  }

  public function reservations()
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




  public function store()
  {
    $this->CSFRToken->check();
    $this->Admin->storeAdmin($_POST);
  }

  public function login()
  {
    session_start();
    $this->initializePOST();
    $admin = $this->Admin->loginAdmin($_POST);

    if ($admin) {
      $this->CSFRToken->check();
      $accessToken = $this->Auth->generateAccessToken($admin);
      $this->Auth->generateRefreshToken($admin);
      http_response_code(200);
      echo json_encode([
        'status' => true,
        'accessToken' => $accessToken,
        'message' => "Sikeres bejelentkezés!"
      ]);
      return;
    } else {
      http_response_code(401);
      echo json_encode([
        'status' => false,
        'message' => "Hibás e-mail vagy jelszó!"
      ]);
    }
  }




  public function logout()
  {
    $this->initializePOST();
    $accessToken = $this->Auth->getTokenFromHeaderOrSendErrorResponse();
    $this->Auth->decodeJwtOrSendErrorResponse($accessToken);
    $this->CSFRToken->check();

    // Ellenőrizzük, hogy a session már el lett-e indítva
    if (session_status() == PHP_SESSION_NONE) {
      session_start();
    }

    session_destroy();

    // Unset the session cookie
    $cookieParams = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $cookieParams["path"], $cookieParams["domain"], $cookieParams["secure"], isset($cookieParams["httponly"]));

    // Unset the refresh token cookie
    if (isset($_COOKIE['refreshToken'])) {
      unset($_COOKIE['refreshToken']);
      setcookie('refreshToken', '', time() - 42000, '/', $cookieParams["domain"], $cookieParams["secure"], true);
    }

    echo json_encode([
      'status' => true,
      'message' => "Sikeres kiejelentkezés!",
      'dev' => "Logout succesfully!",
      'data' => null
    ]);

    return;
  }

  public function index()
  {
    //$this->Auth::checkUserIsLoggedInOrRedirect('adminId', '/admin');


    echo $this->Render->write("admin/Layout.php", [
      "count_of_reservations" => count($this->Reservation->getAllReservationsWithoutAccept()),
      "csfr" => $this->CSFRToken,
      "content" => $this->Render->write("admin/pages/Dashboard.php", [])
    ]);
  }
}
