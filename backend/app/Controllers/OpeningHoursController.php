<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Admin;
use App\Models\OpeningHour;
use DateTime;
use Exception;
use PDO;

class OpeningHoursController extends Controller
{

  private $OpeningHour;
  public function __construct()
  {
    $this->OpeningHour = new OpeningHour();
    parent::__construct();
  }

  public function getOpeningHours()
  {
    try {
      $opening_hours = $this->OpeningHour->all('opening_hours');
      echo json_encode([
        'status' => true,
        'openingHours' => $opening_hours
      ]);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'status' => false,
        'dev' => $e->getMessage()
      ]);
    }
  }

  public function updateOpening($vars)
  {
    try {
      $this->initializePOST();
      $this->OpeningHour->updateOpeningHour($vars['id'], $_POST);
      $updated = $this->Model->selectByRecord('opening_hours', 'id', $vars['id'], PDO::PARAM_INT);
      echo json_encode([
        'status' => true,
        'updated' => $updated
      ]);
    } catch (Exception $e) {
      http_response_code(500);
      echo json_encode([
        'status' => false,
        'dev' => $e->getMessage()
      ]);
    }
  }
}
