<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

$dotenv = Dotenv::createImmutable(__DIR__ . '/');
$dotenv->load();

$capsule = new Capsule;
$capsule->addConnection(require __DIR__ . '/src/config/database.php');
$capsule->setAsGlobal();
$capsule->bootEloquent();

$logger = require __DIR__ . '/src/config/logger.php';

try {
    $router = require __DIR__ . '/src/config/router.php';
    $router->dispatch();
} catch (Exception $e) {
    $logger->error($e->getMessage(), ['exception' => $e]);
    echo "An error occurred. Please check the logs.";
}