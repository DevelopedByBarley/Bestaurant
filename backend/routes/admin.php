<?php
use App\Controllers\AdminController;

// route_group -> /admin


//Renders
$r->addRoute('GET', '', [AdminController::class, 'loginPage']);
$r->addRoute('GET', '/dashboard', [AdminController::class, 'index']);
$r->addRoute('GET', '/reservations', [AdminController::class, 'reservations']);
$r->addRoute('GET', '/opening-hours', [AdminController::class, 'openingHours']);
$r->addRoute('GET', '/holidays', [AdminController::class, 'holidays']);
$r->addRoute('GET', '/capacities', [AdminController::class, 'capacities']);
$r->addRoute('GET', '/admin-list', [AdminController::class, 'adminList']);

$r->addRoute('POST', '/login', [AdminController::class, 'login']);
$r->addRoute('POST', '/store', [AdminController::class, 'store']);
$r->addRoute('POST', '/logout', [AdminController::class, 'logout']);