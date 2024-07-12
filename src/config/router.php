<?php
use Klein\Klein;
use App\Controllers\WeatherController;
use App\Services\WeatherService;

$router = new Klein();
$logger = require __DIR__ . '/logger.php';

$router->respond('GET', '/', function ($request, $response, $service, $app) use ($logger) {
    $weather = new WeatherService($logger);
    $controller = new WeatherController($weather,$logger);
    return $controller->showWeather($request, $response, $service, $app);
});

return $router;