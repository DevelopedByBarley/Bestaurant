<?php

function getConnect()
{
  $servername = $_SERVER['DB_HOST'];
  $username = $_SERVER['DB_USERNAME'];
  $password = $_SERVER['DB_PASSWORD'];
  $dbName = $_SERVER['DB_NAME'];

  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbName", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
  } catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }
}
