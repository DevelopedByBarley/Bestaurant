<?php
namespace App\Services;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class AuthService
{
  public function generateAccessToken($user)
  {
    $key = $_SERVER["JWT_TOKEN_SECRET"];
    $payload = [
      'sub' => $user["id"],
      "email" => $user["email"],
      'iat' => time(),
      'exp' => time() + 5,
    ];


    $accessToken = JWT::encode($payload, $key, 'HS256');

    return $accessToken;
  }


  public function generateRefreshToken($user)
  {
    $key = $_SERVER["JWT_TOKEN_SECRET"];
    $payload = [
      'sub' => $user["id"],
      "email" => $user["email"],
      'iat' => time(),
      'exp' => time() + 60 * 60 * 24 * 30,
    ];

    $refreshToken = JWT::encode($payload, $key, 'HS256');


    setcookie('refreshToken', $refreshToken, [
      'expires' => time() + 60 * 60 * 24 * 30,
      'path' => "/",
      'httponly' => true,
      'secure' => true,
      'samesite' => 'None', // csak fejlesztési célokkal 'None', amúgy 'Lax'
    ]);
  }



  public  function getTokenFromHeaderOrSendErrorResponse()
  {
    $headers = getallheaders();

    $isFound = preg_match(
      '/Bearer\s(\S+)/',
      $headers['authorization'] ?? '',
      $matches
    );
    if (!$isFound) {
      http_response_code(401);
      echo json_encode(['error' => 'unauthorized']);
      exit;
    }
    return $matches[1];
  }


  public function decodeJwtOrSendErrorResponse($token)
  {
    try {
      $decoded = JWT::decode($token, new Key($_SERVER["JWT_TOKEN_SECRET"], 'HS256'));
      return (array)$decoded;
    } catch (\Firebase\JWT\ExpiredException $err) {
      http_response_code(403);
      header('Content-type: application/json');
      echo json_encode(['error' => 'token expired']);
      exit;
    } catch (Exception $exception) {
      http_response_code(401);
      echo json_encode(['error' => 'validation failed']);
      exit;
    }
  }

  public function getNewAccessToken()
  {
    $refreshToken = $_COOKIE["refreshToken"];
    $decoded = self::decodeJwtOrSendErrorResponse($refreshToken);

    $key = $_SERVER["JWT_TOKEN_SECRET"];
    $payload = [
      'sub' => $decoded["sub"],
      "email" => $decoded["email"],
      'iat' => time(),
      'exp' => time() + 5,
    ];

    $accessToken = JWT::encode($payload, $key, 'HS256');

    echo json_encode(["accessToken" => $accessToken]);
  }
}