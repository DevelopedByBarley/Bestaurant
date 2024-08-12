<?php

namespace App\Models;

use PDO;
use PDOException;
use Exception;

class Visitor extends Model
{

  public function addVisitor()
  {
    session_start();
    if(isset($_SESSION['adminId'])) return false;

    try {
        if (!isset($_COOKIE['visitor_session'])) {
          $sessionId =  session_id();
          setcookie("visitor_session", $sessionId, 0);

          $browser = self::getUserBrowser();
          $operatingSystem = php_uname('s');
          $referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Direct';
          $userAgent = $_SERVER['HTTP_USER_AGENT'];
          $deviceType = $this->getDeviceType($userAgent);
          $ip = self::getUserIP();
          $country = $this->getCountryFromIP($ip);

          $stmt = $this->Pdo->prepare(
            "INSERT INTO visits (session_id, ip_address, visit_start, visit_end,  browser, operating_system, referrer, device_type, country) VALUES (:session_id, :ip_address, current_timestamp(), current_timestamp(), :browser, :operating_system, :referrer, :device_type, :country)"
          );
          $stmt->bindParam(':session_id', $sessionId, PDO::PARAM_STR);
          $stmt->bindParam(':ip_address', $ip, PDO::PARAM_STR);
          $stmt->bindParam(':browser', $browser, PDO::PARAM_STR);
          $stmt->bindParam(':operating_system', $operatingSystem, PDO::PARAM_STR);
          $stmt->bindParam(':referrer', $referrer, PDO::PARAM_STR);
          $stmt->bindParam(':device_type', $deviceType, PDO::PARAM_STR);
          $stmt->bindParam(':country', $country, PDO::PARAM_STR);

          $stmt->execute();
        } else {
          // Ha a session él, frissítjük az utolsó látogatási időt
          $sessionId = $_COOKIE['visitor_session'];

          $stmt = $this->Pdo->prepare(
            "UPDATE visits SET visit_end = current_timestamp() WHERE session_id = :session_id"
          );
          $stmt->bindParam(':session_id', $sessionId, PDO::PARAM_STR);
          $stmt->execute();
        }
      
    } catch (PDOException $e) {
      throw new Exception("An error occurred during the database operation in addVisitor method in Visitor model: " . $e->getMessage());
    }
  }

  private function getDeviceType($userAgent)
  {
    if (preg_match('/mobile|android|iphone|ipod/i', $userAgent)) {
      return 'Mobile';
    } elseif (preg_match('/tablet|ipad/i', $userAgent)) {
      return 'Tablet';
    } elseif (preg_match('/laptop|notebook/i', $userAgent)) {
      return 'Laptop';
    } else {
      return 'Desktop';
    }
  }

  private function getCountryFromIP($ip)
  {
      if ($ip == '127.0.0.1' || $ip == '::1') {
          return 'Localhost';
      }
  
      // Ellenőrizd, hogy az IP cím nem privát vagy bogon cím-e
      if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
          return 'Private or Bogon IP';
      }
  
      $accessKey = $_SERVER['IP_ACCESS_KEY']; // Cseréld ki a saját API kulcsodra
      $url = "https://ipinfo.io/{$ip}?token={$accessKey}";
  
      $json = @file_get_contents($url);
  
      if ($json === false) {
          return 'Unknown';
      }
  
      $data = json_decode($json, true);
  
      if (isset($data['country'])) {
          return trim($data['country']);
      } elseif (isset($data['bogon']) && $data['bogon'] === true) {
          return 'Private or Bogon IP';
      } else {
          return 'Unknown';
      }
  }
  private function getUserIP()
  {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      return $_SERVER['REMOTE_ADDR'];
    }
  }

  // Funkció a felhasználó böngészőjének lekérdezésére
  private function getUserBrowser()
  {
    return $_SERVER['HTTP_USER_AGENT'];
  }
}
