<?php
namespace App\Controllers;

use App\Services\WeatherService;
use Monolog\Logger;
use Exception;

class WeatherController {
    private $logger;

    public function __construct(Logger $logger) {
        $this->logger = $logger;
    }

    public function showWeather($request, $response, $service, $app) {
        try {
            $lat = $request->param('lat',$_ENV['DEFAULT_LAT']);
            $lon = $request->param('lon',$_ENV['DEFAULT_LON']);
            $format = $request->param('format', $_ENV['DEFAULT_FORMAT']);
            $weather = new WeatherService($this->logger,$lat,$lon,$format);
            $data = $weather->getWeather();
            if ($data) {
                $service->render(__DIR__ . '/../views/weather.php', ['data' => $data, 'lat' => $lat,'lon'=>$lon]);
            } else {
                $response->code(404);
                $response->send('Weather data not found.');
            }
        } catch (Exception $error) {
            $this->logger->error($error->getMessage(), ['exception' => $error]);
            $response->code(500);
            $response->send('Error Occured');
        }
    }
    public function syncWeather($request, $response, $service, $app) {
        try {
            $lat = $request->param('lat',$_ENV['DEFAULT_LAT']);
            $lon = $request->param('lon',$_ENV['DEFAULT_LON']);
            $format = $request->param('format', $_ENV['DEFAULT_FORMAT']);
            $weather = new WeatherService($this->logger,$lat,$lon,$format);
            $data = $weather->syncWeather();
            if ($data) {
                $service->render(__DIR__ . '/../views/sync.php', ['data' => $data, 'lat' => $lat,'lon'=>$lon]);
            } else {
                $response->code(404);
                $response->send('Weather data not found.');
            }
        } catch (Exception $error) {
            $this->logger->error($error->getMessage(), ['exception' => $error]);
            $response->code(500);
            $response->send('Error Occured');
        }
    }
}