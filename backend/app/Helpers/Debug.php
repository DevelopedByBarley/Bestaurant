<?php

namespace App\Helpers;

class Debug
{
  public function writeToConsole($data)
  {
    if (is_array($data)) {
      $output = json_encode($data);
    } else {
      $output = strval($data);
    }

    echo "<script>console.log('$output');</script>";
  }
}
