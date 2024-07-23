<?php

use App\Controllers\OpeningHoursController;
use App\Services\AuthService;

// route_group -> /


$r->addRoute('GET', '', [OpeningHoursController::class, 'getOpeningHours']);
$r->addRoute('POST', '/update/{id}', [OpeningHoursController::class, 'updateOpening']);

