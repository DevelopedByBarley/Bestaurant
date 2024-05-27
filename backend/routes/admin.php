<?php

use App\Controllers\AdminController;

// route_group -> /admin


//Renders
$r->addRoute('GET', '/reservations', [AdminController::class, 'reservations']);

$r->addRoute('POST', '/login', [AdminController::class, 'login']);
$r->addRoute('POST', '/store', [AdminController::class, 'store']);
$r->addRoute('POST', '/reservations/cancel/{id}', [AdminController::class, 'cancelReservation']);
$r->addRoute('POST', '/reservations/delete/{id}', [AdminController::class, 'deleteReservation']);
$r->addRoute('POST', '/reservations/accept/{id}', [AdminController::class, 'acceptReservation']);
$r->addRoute('POST', '/logout', [AdminController::class, 'logout']);
