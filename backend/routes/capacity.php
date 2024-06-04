<?php

use App\Controllers\CapacityController;

$r->addRoute('GET', '', [CapacityController::class, 'getAllCapacities']);
$r->addRoute('GET', '/{date}', [CapacityController::class, 'index']);
$r->addRoute('POST', '/delete/{id}', [CapacityController::class, 'destroy']);
$r->addRoute('POST', '/update/{id}', [CapacityController::class, 'updateExceptionCapacity']);
