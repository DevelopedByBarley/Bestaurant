<?php

use App\Controllers\Controller;
use App\Services\AuthService;

// route_group -> /


$r->addRoute('GET', '', [Controller::class, 'render']);
$r->addRoute('GET', 'get-token', [AuthService::class, 'getNewAccessToken']);
$r->addRoute('GET', 'cookie-info', [Controller::class, 'cookie']);
$r->addRoute('GET', 'token', [Controller::class, 'token']);
$r->addRoute('POST', 'test', [Controller::class, 'test']);
