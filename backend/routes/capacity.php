<?php

use App\Controllers\CapacityController;

$r->addRoute('GET', '', [CapacityController::class, 'getAllCapacities']);
$r->addRoute('GET', '/create-default', [CapacityController::class, 'getAllCapacities']);
$r->addRoute('GET', '/{date}', [CapacityController::class, 'index']);

$r->addRoute('POST', '/new', [CapacityController::class, 'store']);

$r->addRoute('POST', '/update/default/{id}', [CapacityController::class, 'updateNextDefault']);
$r->addRoute('POST', '/new/default', [CapacityController::class, 'storeDefault']);
$r->addRoute('POST', '/delete/{id}', [CapacityController::class, 'destroy']);
$r->addRoute('POST', '/update/{id}', [CapacityController::class, 'updateExceptionCapacity']);
