<?php
namespace App\Helpers;


class Toast
{
  // SET ALERT
  public function set($message, $bg, $location, $messageInEng = null)
  {

    if (session_id() == '') {
      session_start();
    }

    $lang = isset($_COOKIE["lang"]) ? $_COOKIE["lang"] : null;

    if ($lang === "Hu") {
      $_SESSION["toast"] = [
        "message" => $message,
        "bg" => $bg,
        "color" => 'white',
        "time" => 'most',
        "expires" => time() + 2,
      ];
    } else if ($lang === "En") {
      $_SESSION["toast"] = [
        "message" => $messageInEng,
        "bg" => $bg,
        "expires" => time() + 2
      ];


    } else {
      // Hibakezelés, ha a nyelv nem "Hu" vagy "En"
      echo "Hiba: Ismeretlen nyelv!";
      exit;
    }


    header("Location: $location");
    exit;
  }
}