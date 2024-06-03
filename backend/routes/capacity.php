<?php

use App\Controllers\CapacityController;

$r->addRoute('GET', '', [CapacityController::class, 'getAllCapacities']);
$r->addRoute('GET', '/{date}', [CapacityController::class, 'index']);
