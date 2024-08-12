<?php

use App\Controllers\Controller;

// route_group -> /


$r->addRoute('GET', '', [Controller::class, 'index']);
$r->addRoute('GET', 'cookie-info', [Controller::class, 'cookie']);
$r->addRoute('GET', 'test', [Controller::class, 'test']);
