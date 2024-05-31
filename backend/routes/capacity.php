<?php

use App\Controllers\CapacityController;

$r->addRoute('GET', '/{date}', [CapacityController::class, 'index']);
