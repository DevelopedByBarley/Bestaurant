<?php

use App\Models\Visitor;
use App\Services\LanguageService;


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$langService = new LanguageService();
$langService->language();

require_once 'config/meta/index.meta.php';
require_once 'config/variables/avatars.php';
require_once 'config/app/langs.php';
require_once 'config/app/app.php';
require_once 'config/app/database.php';



require_once 'config/app/router.php';
