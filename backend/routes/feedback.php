<?php

use App\Controllers\FeedbackController;

// route_group -> /feedback


$r->addRoute('GET', '', [FeedbackController::class, 'feedback']);
$r->addRoute('POST', '', [FeedbackController::class, 'storeFeedback']);
