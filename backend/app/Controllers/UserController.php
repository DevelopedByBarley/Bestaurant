<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\User;

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
      "csfr" => $this->CSFRToken,
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
        "csfr" => $this->CSFRToken
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
        "csfr" => $this->CSFRToken
      ])
    ]);
  }

  public function store()
  {
    $this->CSFRToken->check();
    session_start();

    $alert = $this->User->storeUser($_POST, $_FILES);
    $this->Alert->set($alert['hu'], $alert['bg'], $alert['redirect'], $alert['en']);
  }



  public function login()
  {
    $this->CSFRToken->check();


    $userId = $this->User->loginUser($_POST);


    if ($userId) {
      $_SESSION['userId'] = $userId;
      $session_timeout = 5;
      session_set_cookie_params($session_timeout, '/', '', true, true); // secure és httponly flag beállítása
      session_regenerate_id(true);
    }

    self::redirectByState($$userId, '/user/dashboard', '/user/login');
  }




  public function logout()
  {

    $token = $this->CSFRToken->check();
    session_start();
    session_destroy();
    session_regenerate_id(true);

    $cookieParams = session_get_cookie_params();
    setcookie(session_name(), "", 0, $cookieParams["path"], $cookieParams["domain"], $cookieParams["secure"], isset($cookieParams["httponly"]));

    header("Location: /user/login");
  }


}
