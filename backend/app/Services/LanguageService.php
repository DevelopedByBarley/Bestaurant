<?php

namespace App\Services;

class LanguageService
{
  public function language()
  {
    $expiration_date = time() + (7 * 24 * 60 * 60);
    $ret = "";

    // Böngésző által preferált nyelv kinyerése
    $browser_language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';
    $preferred_languages = explode(',', $browser_language);
    $language = strtolower(trim(explode(';', $preferred_languages[0])[0]));

    // Engedélyezett nyelvek ellenőrzése
    if ($language === "hu-hu") {
      $ret = "Hu";
    } else {
      $ret = "En";
    }

    // Biztonságos sütikezelés
    $cookie_name = "lang";
    $cookie_value = ($ret === "Hu" || $ret === "En") ? $ret : "En"; // Csak engedélyezett értékek
    setcookie($cookie_name, $cookie_value, $expiration_date, "/", "", false, false); // HttpOnly és Secure opciók
  }

  // NYELV VÁLTÁSA

  public function switchLanguage($lang)
  {
    $expiration_date = time() + (7 * 24 * 60 * 60);
    $referer = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '/'; // Ellenőrizze a referer URL-t

    // Engedélyezett nyelvek ellenőrzése
    $cookie_name = "lang";
    $cookie_value = ($lang === "Hu" || $lang === "En") ? $lang : "En";

    // Biztonságos sütikezelés
    setcookie($cookie_name, $cookie_value, $expiration_date, "/", "", false, false); // HttpOnly és Secure opciók

    // Visszatérés a referer URL-re
    header("Location: $referer");
    exit();
  }
}
