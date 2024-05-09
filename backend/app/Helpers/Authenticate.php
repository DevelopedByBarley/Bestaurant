<?php

namespace App\Helpers;

class Authenticate
{
  private static function isLoggedIn($entity)
  {
  
    if (!isset($_COOKIE[session_name()])) return false;
    if (session_id() == '') {
      session_start();
    }
    if (!isset($_SESSION[$entity])) return false;
    return true;
  }


  public static function checkUserIsLoggedInOrRedirect($entity, $redirect)
  {
    if (self::isLoggedIn($entity)) {
      return $_SESSION[$entity];
    };

    header("Location: $redirect");
    exit;
  }
}
