<?php

use App\Controllers\Controller;
use App\Controllers\ReservationController;

$r->addRoute('POST', '', [ReservationController::class, 'getReservations']);
$r->addRoute('POST', '/new', [ReservationController::class, 'store']);

