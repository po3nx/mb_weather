<?php
use Klein\Klein;
use App\Controllers\WeatherController;

$router = new Klein();
$logger = require __DIR__ . '/logger.php';
try {
    $router->respond('GET', '/', function ($request, $response, $service, $app) use ($logger) {
        $controller = new WeatherController($logger);
        return $controller->showWeather($request, $response, $service, $app);
    });
    $router->respond('GET', '/sync', function ($request, $response, $service, $app) use ($logger) {
        $controller = new WeatherController($logger);
        return $controller->syncWeather($request, $response, $service, $app);
    });
} catch (Exception $error) {
    $this->logger->error($error->getMessage(), ['exception' => $error]);
    $response->code(500);
    $response->send('Error Occured');
}

return $router;