<?php
namespace App\Controllers;

use App\Services\WeatherService;
use Monolog\Logger;
use Exception;

class WeatherController {
    private $weather;
    private $logger;

    public function __construct(WeatherService $weather, Logger $logger) {
        $this->weather = $weather;
        $this->logger = $logger;
    }

    public function showWeather($request, $response, $service, $app) {
        try {
            $lat = $request->param('lat',$_ENV['DEFAULT_LAT']);
            $lon = $request->param('lon',$_ENV['DEFAULT_LON']);
            $format = $request->param('format', $_ENV['DEFAULT_FORMAT']);
            $data = $this->weather->fetchWeather($lat, $lon, $format);
            if ($data) {
                $service->render(__DIR__ . '/../views/weather.php', ['data' => $data, 'lat' => $lat,'lon'=>$lon]);
            } else {
                $response->code(404);
                $response->send('Weather data not found.');
            }
        } catch (Exception $error) {
            $this->logger->error($error->getMessage(), ['exception' => $error]);
        }
    }
}