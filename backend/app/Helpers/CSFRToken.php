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

  public function token()
  {
    if (session_id() == '') {
      session_start();
    }

    // Generálunk egy véletlenszerű token-t
    $token = bin2hex(random_bytes(32)); // Erősebb véletlenszerű token generálás

    // Kódoljuk a token-t a titkos kulcs segítségével
    $encodedToken = hash_hmac('sha256', $token, $this->secretKey);

    // Tároljuk el a kódolt token-t a session-ben, de előtte ellenőrizzük, hogy a session-ben már van-e csrf tömb
    if (isset($_SESSION['csrf']) && is_array($_SESSION['csrf'])) {
      // Ha már van csrf tömb a session-ben, akkor adjuk hozzá a generált tokent
      $_SESSION['csrf'][] = $encodedToken;
    } else {
      // Ha még nincs csrf tömb a session-ben, akkor hozzunk létre újat és tegyük bele a generált tokent
      $_SESSION['csrf'] = [$encodedToken];
    }

    // Tároljuk el a kódolt token-t a visszatérési értékben
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
    $encodedToken = hash_hmac('sha256', $token, $this->secretKey);

    // Tároljuk el a kódolt token-t a session-ben, de előtte ellenőrizzük, hogy a session-ben már van-e csrf tömb
    if (isset($_SESSION['csrf']) && is_array($_SESSION['csrf'])) {
      // Ha már van csrf tömb a session-ben, akkor adjuk hozzá a generált tokent
      $_SESSION['csrf'][] = $encodedToken;
    } else {
      // Ha még nincs csrf tömb a session-ben, akkor hozzunk létre újat és tegyük bele a generált tokent
      $_SESSION['csrf'] = [$encodedToken];
    }

    // A token-t használhatjuk a felhasználói felületen, például egy rejtett input mezőként egy űrlapon
    echo "<input type='hidden' name='csrf' value='$token'>";
  }


  public function check()
  {
    if (session_id() == '') {
      session_start();
    }

    if (!isset($_POST['csrf'])) {
      http_response_code(401);
      echo 'Post csrf problem';
      exit;
    }

    if (!isset($_SESSION['csrf'])) {
      var_dump($_SESSION['csfr']);
      http_response_code(401);
      echo 'Session csrf problem';
      exit;
    }

    $postCsrf = $_POST['csrf'];

    // Iteráljunk végig a session CSRF tömbön
    foreach ($_SESSION['csrf'] as $key => $sessionCsrf) {
      // Kódoljuk a token-t a titkos kulcs segítségével
      $token = hash_hmac('sha256', $postCsrf, $this->secretKey);

      // Ellenőrizzük, hogy a post CSRF token megegyezik-e a session-ben tárolt CSRF token-nel
      if (hash_equals($sessionCsrf, $token)) {
        // Ha megegyezik, töröljük a session-ből ezt a CSRF tokent
        unset($_SESSION['csrf'][$key]);
        break; // Kilépünk a ciklusból, mert megtaláltuk a passzoló CSRF tokent
      }
    }

    // Ha a session-ben többé nincs CSRF token
    if (empty($_SESSION['csrf'])) {
      unset($_SESSION['csrf']); // Töröljük a CSRF tömböt a session-ből
    }

    return true;
  }


  private function isSafeOrigin()
  {
    // Az elfogadható eredetek listája
    $safeOrigins = array('http://localhost:8080', 'http://localhost:9090', 'http://localhost:3000', 'https://barley-test.hu');

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
