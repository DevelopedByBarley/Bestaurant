<?php

use App\Controllers\CapacityController;

$r->addRoute('GET', '', [CapacityController::class, 'index']);
