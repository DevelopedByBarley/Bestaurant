<?php

use App\Controllers\HolidayController;
use App\Services\AuthService;

// route_group -> /api/holidays


$r->addRoute('GET', '', [HolidayController::class, 'getHolidays']);
$r->addRoute('POST', '', [HolidayController::class, 'storeHoliday']);
$r->addRoute('POST', '/delete/{id}', [HolidayController::class, 'deleteHoliday']);
