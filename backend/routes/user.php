<?php

use App\Controllers\UserController;

// route_group -> /


$r->addRoute('GET', '/register', [UserController::class, 'registerPage']);
$r->addRoute('GET', '/login', [UserController::class, 'loginPage']);
$r->addRoute('GET', '/dashboard', [UserController::class, 'index']);
$r->addRoute('POST', '/register', [UserController::class, 'store']);
$r->addRoute('POST', '/login', [UserController::class, 'login']);
$r->addRoute('POST', '/logout', [UserController::class, 'logout']);
