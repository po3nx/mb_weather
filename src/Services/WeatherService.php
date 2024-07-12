<?php 
namespace App\Services;

use InvalidArgumentException;
use App\Services\HttpService;
use Monolog\Logger;

class WeatherService {
    public $logger;
    public function __construct(Logger $logger) {
        $this->logger = $logger;
    }

    public function fetchWeather($lat, $lon, $format) {
        $url = ($_ENV['APP_ENV'] === 'prod') ?
            "https://my.meteoblue.com/packages/basic-1h?" . http_build_query([
                "apikey" => $_ENV['API_KEY'],
                "lat" => $lat,
                "lon" => $lon,
                "format" => $format
            ]) : "https://my.meteoblue.com/packages/basic-10min_basic-1h_basic-day?" . http_build_query([
                "lat" => "47.56",
                "lon" => "7.57",
                "apikey" => "DEMOKEY",
                "sig" => "6dc30666add97137e75d10d98debedbb"
            ]);

        try {
            //$response = self::curlGetContents($url);
            $response = HttpService::makeRequest($url);
            $data = json_decode($response, true);

            return $data;
        } catch (InvalidArgumentException $error) {
            $this->logger->error($error->getMessage(), ['exception' => $error]);
            return null;
        }
    }
    
}