<?php

use App\Controllers\ReservationController;

$r->addRoute('POST', '', [ReservationController::class, 'test']);

