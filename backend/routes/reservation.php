<?php

use App\Controllers\ReservationController;

// Admin

$r->addRoute('GET', '', [ReservationController::class, 'index']);

$r->addRoute('POST', '/cancel/{id}', [ReservationController::class, 'cancelReservation']);
$r->addRoute('POST', '/delete/{id}', [ReservationController::class, 'deleteReservation']);
$r->addRoute('POST', '/accept/{id}', [ReservationController::class, 'acceptReservation']);

// Public

$r->addRoute('POST', '', [ReservationController::class, 'getReservations']);
$r->addRoute('POST', '/new', [ReservationController::class, 'store']);
