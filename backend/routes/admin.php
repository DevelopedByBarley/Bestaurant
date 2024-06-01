<?php

use App\Controllers\AdminController;

// route_group -> /api/admin

$r->addRoute('POST', '/login', [AdminController::class, 'login']);
$r->addRoute('POST', '/store', [AdminController::class, 'store']);

$r->addRoute('POST', '/logout', [AdminController::class, 'logout']);
