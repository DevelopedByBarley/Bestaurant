<?php

namespace App\Controllers;

use App\Helpers\Alert;
use App\Helpers\Authenticate;
use App\Helpers\CSFRToken;
use App\Helpers\Debug;
use App\Helpers\Render;
use App\Helpers\Toast;
use App\Helpers\UUID;
use App\Helpers\XLSX;
use App\Models\Model;
use App\Services\AuthService;
use App\Services\LanguageService;

class Controller
{
  protected $Model;
  protected $Debug;
  protected $Auth;
  protected $Render;
  protected $XLSX;
  protected $UUID;
  protected $Alert;
  protected $Toast;
  protected $CSFRToken;


  public function __construct()
  {
    $this->Model = new Model();
    $this->Debug = new Debug();
    $this->Auth = new AuthService();
    $this->Render = new Render();
    $this->XLSX = new XLSX();
    $this->UUID = new UUID();
    $this->Alert = new Alert();
    $this->Toast = new Toast();
    $this->CSFRToken = new CSFRToken();
  }



  public function testMail()
  {
    $this->Model->sendMail();
  }

  public function token()
  {
    header('X-CSRF-Token: ' . $this->CSFRToken->token());
  }

  public function render()
  {
    echo $this->Render->write("public/Layout.php", [
      "content" => $this->Render->write("../../../frontend/build/index.html", [ ])
    ]);
  }
  public function cookie()
  {
    echo $this->Render->write("public/Layout.php", [
      "content" => $this->Render->write("public/pages/Cookie_Info.php", [])
    ]);
  }

  public function error()
  {
    echo $this->Render->write("public/Layout.php", [
      "content" => $this->Render->write("public/pages/404.php", [])
    ]);
  }

  public function redirectByState($isSuccess, $success_url, $failed_url)
  {
    if ($isSuccess) {
      header("Location: $success_url");
      exit;
    } else {
      header("Location: $failed_url");
      exit;
    }
  }

  public function initializePOST()
  {
    $_POST = json_decode(file_get_contents('php://input'), true);
  }

}
