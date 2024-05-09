<?php
namespace App\Helpers;


class Alert
{
  // SET ALERT
  public function set($message, $bg, $location, $messageInEng = null)
  {

    if (session_id() == '') {
      session_start();
    }

    $lang = isset($_COOKIE["lang"]) ? $_COOKIE["lang"] : null;

    if ($lang === "Hu") {
      $_SESSION["alert"] = [
        "message" => $message,
        "bg" => $bg,
        "expires" => time() + 2
      ];
    } else if ($lang === "En") {
      $_SESSION["alert"] = [
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
