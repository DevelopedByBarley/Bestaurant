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





  function validateDate($date, $format = 'Y-m-d')
  {
    if ($date) {
      $d = DateTime::createFromFormat($format, $date);
      return $d && $d->format($format) === $date;
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
}
