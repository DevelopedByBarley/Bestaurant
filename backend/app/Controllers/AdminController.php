<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Admin;
use App\Models\Capacity;
use App\Models\Holiday;
use App\Models\Reservation;
use DateTime;
use PDO;

class AdminController extends Controller
{
  private $Admin;
  private $Reservation;
  private $Capacity;
  private $Holiday;

  public function __construct()
  {
    $this->Admin = new Admin();
    $this->Reservation = new Reservation();
    $this->Capacity = new Capacity();
    $this->Holiday = new Holiday();
    parent::__construct();
  }


  public function loginPage()
  {
    session_start();

    $admin = $_SESSION["adminId"] ?? null;

    if ($admin) {
      header("Location: /admin/dashboard");
      exit;
    }


    echo $this->Render->write("admin/Layout.php", [
      "content" => $this->Render->write("admin/pages/Login.php", [
        "csfr" => $this->CSFRToken
      ])
    ]);
  }



  function validateDate($date, $format = 'Y-m-d')
  {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
  }

  public function reservations()
  {
    $this->Auth->checkUserIsLoggedInOrRedirect('adminId', '/');
    $today = date("Y-m-d", time());
    $search = filter_input(INPUT_GET, 'date', FILTER_SANITIZE_SPECIAL_CHARS) ?? $today;


    $searchResult = $this->Model->searchBySingleEntity('reservations', 'date', $search, $today);

    $reservations = $this->Model->paginate(
      $searchResult,
      10,
      $search,
      function ($offset, $numOfPage, $search) {
        $min = '2024-01-01';
        if (isset($_GET["offset"]) && $offset > $numOfPage || isset($_GET["offset"]) && !is_numeric($_GET["offset"]) || !self::validateDate($search) || $search < $min) {
          $this->Toast->set('Inkorrekt dátum vagy oldalszám!', 'danger', "/admin/reservations");
          exit;
        }
      }

    );





    echo $this->Render->write("admin/Layout.php", [
      "count_of_reservations" => count($this->Reservation->getAllReservationsWithoutAccept()),
      "csfr" => $this->CSFRToken,
      "content" => $this->Render->write("admin/pages/Reservations.php", [
        "data" => $reservations
      ])
    ]);
  }

  public function holidays()
  {

    $this->Auth::checkUserIsLoggedInOrRedirect('adminId', '/admin');

    $results = $this->Holiday->holidays();
    $holidays = $this->Model->paginate($results, 10, '', function () {});

 

    echo $this->Render->write("admin/Layout.php", [
      "csfr" => $this->CSFRToken,
      "count_of_reservations" => count($this->Reservation->getAllReservationsWithoutAccept()),
      "content" => $this->Render->write("admin/pages/Holidays.php", [
        "data" => $holidays
      ])
    ]);
  }

  public function openingHours()
  {

    $this->Auth::checkUserIsLoggedInOrRedirect('adminId', '/admin');
    $opening_hours = $this->Model->all('opening_hours');



    echo $this->Render->write("admin/Layout.php", [
      "csfr" => $this->CSFRToken,
      "count_of_reservations" => count($this->Reservation->getAllReservationsWithoutAccept()),
      "content" => $this->Render->write("admin/pages/OpeningHours.php", [
        "opening_hours" => $opening_hours
      ])
    ]);
  }


  public function capacities()
  {
    $this->Auth::checkUserIsLoggedInOrRedirect('adminId', '/admin');

    $default_capacity = $this->Model->selectByRecord('capacities', 'is_default', 1, PDO::PARAM_INT)['capacity'];
    $results = $this->Capacity->capacities();
    $capacities = $this->Model->paginate($results, 10, '', function () {
    });

    echo $this->Render->write("admin/Layout.php", [
      "count_of_reservations" => count($this->Reservation->getAllReservationsWithoutAccept()),
      "csfr" => $this->CSFRToken,
      "content" => $this->Render->write("admin/pages/Capacities.php", [
        "csfr" => $this->CSFRToken,
        "data" => $capacities,
        "default_capacity" => $default_capacity
      ])
    ]);
  }



  public function adminList()
  {

    $this->Auth::checkUserIsLoggedInOrRedirect('adminId', '/admin');


    $results = $this->Model->all('admins');
    $admins = $this->Model->paginate($results, 10, '', function () {
    });



    echo $this->Render->write("admin/Layout.php", [
      "csfr" => $this->CSFRToken,
      "count_of_reservations" => count($this->Reservation->getAllReservationsWithoutAccept()),
      "content" => $this->Render->write("admin/pages/AdminList.php", [
        "data" => $admins
      ])
    ]);
  }



  public function store()
  {
    $this->CSFRToken->check();
    $this->Admin->storeAdmin($_POST);
  }

  public function login()
  {
    $this->CSFRToken->check();
    session_start();

    $isSuccess = $this->Admin->loginAdmin($_POST);

    if ($isSuccess) {
      $session_timeout = 5;
      session_set_cookie_params($session_timeout, '/', '', true, true); // secure és httponly flag beállítása
      session_regenerate_id(true);
    }

    self::redirectByState($isSuccess, '/admin/dashboard', '/admin');
  }




  public function logout()
  {

    $this->CSFRToken->check();
    session_start();
    session_destroy();
    session_regenerate_id(true);

    $cookieParams = session_get_cookie_params();
    setcookie(session_name(), "", 0, $cookieParams["path"], $cookieParams["domain"], $cookieParams["secure"], isset($cookieParams["httponly"]));

    header("Location: /admin");
  }

  public function index()
  {
    $this->Auth::checkUserIsLoggedInOrRedirect('adminId', '/admin');


    echo $this->Render->write("admin/Layout.php", [
      "count_of_reservations" => count($this->Reservation->getAllReservationsWithoutAccept()),
      "csfr" => $this->CSFRToken,
      "content" => $this->Render->write("admin/pages/Dashboard.php", [])
    ]);
  }
}
