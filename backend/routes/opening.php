<?php

use App\Controllers\OpeningHoursController;
use App\Services\AuthService;

// route_group -> /


$r->addRoute('GET', '', [OpeningHoursController::class, 'getOpeningHours']);

