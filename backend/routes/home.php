<?php

use App\Controllers\Controller;

// route_group -> /


$r->addRoute('GET', '', [Controller::class, 'index']);
$r->addRoute('GET', 'cookie-info', [Controller::class, 'cookie']);
$r->addRoute('GET', 'token', [Controller::class, 'token']);
$r->addRoute('POST', 'test', [Controller::class, 'test']);
