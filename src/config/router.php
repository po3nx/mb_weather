<?php
use Klein\Klein;
use App\Controllers\WeatherController;

$router = new Klein();
$logger = require __DIR__ . '/logger.php';
try {
    $router->respond('GET', '/forecast/', function ($request, $response, $service, $app) use ($logger) {
        $controller = new WeatherController($logger);
        return $controller->showWeather($request, $response, $service, $app);
    });
    $router->respond('GET', '/forecast/check', function ($request, $response, $service, $app) use ($logger) {
        $controller = new WeatherController($logger);
        return $controller->checkWeather($request, $response, $service, $app);
    });
    $router->respond('GET', '/forecast/sync', function ($request, $response, $service, $app) use ($logger) {
        $controller = new WeatherController($logger);
        return $controller->syncWeather($request, $response, $service, $app);
    });
} catch (Exception $error) {
    echo "Test";
    $this->logger->error($error->getMessage(), ['exception' => $error]);
    $response->code(500);
    $response->send('Error Occured');
}

return $router;