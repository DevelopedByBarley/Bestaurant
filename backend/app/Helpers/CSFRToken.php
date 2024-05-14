<?php

namespace App\Helpers;

class CSFRToken
{
  private $secretKey;
  public function __construct()
  {
    // A titkos kulcs inicializálása
    $this->secretKey = $_SERVER["CSFR_SECRET"];
  }

  public function token() {
    if (session_id() == '') {
      session_start();
    }

    // Generálunk egy véletlenszerű token-t
    $token = bin2hex(random_bytes(32)); // Erősebb véletlenszerű token generálás

    // Kódoljuk a token-t a titkos kulcs segítségével
    $encodedToken = hash_hmac('sha256', $token,  $this->secretKey);

    $_SESSION['csrf'] = $encodedToken;


    // Tároljuk el a kódolt token-t a session-ben
    return $token;
  }

  public function generate()
  {
    if (session_id() == '') {
      session_start();
    }

    // Generálunk egy véletlenszerű token-t
    $token = bin2hex(random_bytes(32)); // Erősebb véletlenszerű token generálás

    // Kódoljuk a token-t a titkos kulcs segítségével
    $encodedToken = hash_hmac('sha256', $token,  $this->secretKey);

    // Tároljuk el a kódolt token-t a session-ben
    $_SESSION['csrf'] = $encodedToken;

    echo "<input type='hidden' name='csrf' value='$token'>";
  }

  public function check()
  {
    
    if (session_id() == '') {
      session_start();
    }


    if (!isset($_POST['csrf'])) {
      header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
      exit;
    }

    // Kódoljuk a token-t a titkos kulcs segítségével
    $token = hash_hmac('sha256', $_POST['csrf'], $this->secretKey);
   
    if (!hash_equals($_SESSION['csrf'], $token)) {

      header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
      exit;
    }


    if (!$this->isSafeOrigin()) {
      header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
      exit;
    }

    unset($_SESSION['csrf']);
    return true;
  }

  private function isSafeOrigin()
  {
      // Az elfogadható eredetek listája
      $safeOrigins = array('http://localhost:8080', 'http://localhost:9090', 'http://localhost:3000');

      // Ellenőrizzük az Origin fejlécet
      if (isset($_SERVER['HTTP_ORIGIN'])) {
          $origin = rtrim($_SERVER['HTTP_ORIGIN'], '/');
          if (in_array($origin, $safeOrigins)) {
              return true;
          }
      }

      return false;
  }
}