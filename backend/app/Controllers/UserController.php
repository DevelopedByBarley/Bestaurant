<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\User;
use Exception;

class UserController extends Controller
{
  private $User;

  public function __construct()
  {
    $this->User = new User();
    parent::__construct();
  }

  public function index()
  {
    $userId = $this->Auth->checkUserIsLoggedInOrRedirect('userId', '/user/login');

    echo $this->Render->write("public/Layout.php", [
      "csrf" => $this->CSRFToken,
      "content" => $this->Render->write("public/pages/user/Dashboard.php", [
        "user" => $this->Model->show('users', $userId)

      ])
    ]);
  }

  public function registerPage()
  {
    session_start();


    $user = $_SESSION["userId"] ?? null;

    if ($user) {
      header("Location: /user/dashboard");
      exit;
    }


    echo $this->Render->write("public/Layout.php", [
      "content" => $this->Render->write("public/pages/user/Register.php", [
        "csrf" => $this->CSRFToken
      ])
    ]);
  }



  public function loginPage()
  {
    session_start();

    $user = $_SESSION["userId"] ?? null;

    if ($user) {
      header("Location: /user/dashboard");
      exit;
    }


    echo $this->Render->write("public/Layout.php", [
      "content" => $this->Render->write("public/pages/user/Login.php", [
        "csrf" => $this->CSRFToken
      ])
    ]);
  }

  public function store()
  {
    session_start();
    $this->CSRFToken->check();

    $isSuccess = $this->User->storeUser($_POST, $_FILES);

    if (!$isSuccess) {
      $this->Toast->set('Regisztráció sikertelen, próbálja meg más adatokkal!', 'danger', '/user/register', null);
    }

    $this->Toast->set('Regisztráció sikeres!', 'success', '/user/login', null);
  }



  public function login()
  {
    try {
      $this->CSRFToken->check();


      $userId = $this->User->loginUser($_POST);


      if ($userId) {
        session_write_close(); // Bezárjuk a sessiont
        $session_timeout = 6000;
        session_set_cookie_params($session_timeout, '/', '', true, true); // secure és httponly flag beállítása
        session_start();
        session_regenerate_id(true);
        $_SESSION['userId'] = $userId;
        header('Location: /user/dashboard');
        exit;
      } else {
        $this->Toast->set('Hibás e-mail cím vagy jelszó!', 'danger', '/user/login', null);
      }
    } catch (Exception $e) {
      http_response_code(500);
      echo "Internal Server Error" . $e->getMessage();
      exit;
    }
  }




  public function logout()
  {
    try {
      $this->CSRFToken->check();
      session_start();
      session_destroy();
      session_regenerate_id(true);

      $cookieParams = session_get_cookie_params();
      setcookie(session_name(), "", 0, $cookieParams["path"], $cookieParams["domain"], $cookieParams["secure"], isset($cookieParams["httponly"]));

      header("Location: /user/login");
      exit;
    } catch (Exception $e) {
      http_response_code(500);
      echo "Internal Server Error" . $e->getMessage();
      exit;
    }
  }
}
