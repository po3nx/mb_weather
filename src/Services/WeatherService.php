<?php 
namespace App\Services;

use InvalidArgumentException;
use App\Services\HttpService;
use Monolog\Logger;
use App\Models\Weather;

class WeatherService {
    public $logger;
    public $url;
    public $lat;
    public $lon;
    public $format;
    public function __construct(Logger $logger, $lat, $lon, $format) {
        $this->logger = $logger;
        $this->lat = $lat;
        $this->lon = $lon;
        $this->format = $format;
        $this->url =  ($_ENV['APP_ENV'] === 'prod') ?
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
    }

    public function getWeather() {
        try {
            $time = date('Y-m-d');
            $data = Weather::where('lat',$this->lat)
                        ->where('lon',$this->lon)
                        ->whereDate('time',$time)
                        ->get();
            return $data;
        } catch (InvalidArgumentException $error) {
            $this->logger->error($error->getMessage(), ['exception' => $error]);
            return null;
        }
    }
    public function syncWeather() {
        try {
            //$response = HttpService::curlGetContents($url);
            $response = HttpService::makeRequest($this->url);
            $data = json_decode($response, true);

            //save the data to database
            foreach($data['data_1h']['time'] as $key => $date){
                Weather::updateOrCreate(
                    [
                        'time' => $date,
                        'lat' => $this->lat,
                        'lon' => $this->lon
                    ],
                    [
                        'snowfraction' => $data['data_1h']['snowfraction'][$key],
                        'windspeed' => $data['data_1h']['windspeed'][$key],
                        'temperature' => $data['data_1h']['temperature'][$key],
                        'precipitation_probability' => $data['data_1h']['precipitation_probability'][$key],
                        'convective_precipitation' => $data['data_1h']['convective_precipitation'][$key],
                        'rainspot' => $data['data_1h']['rainspot'][$key],
                        'pictocode' => $data['data_1h']['pictocode'][$key],
                        'felttemperature' => $data['data_1h']['felttemperature'][$key],
                        'precipitation' => $data['data_1h']['precipitation'][$key],
                        'isdaylight' => $data['data_1h']['isdaylight'][$key],
                        'uvindex' => $data['data_1h']['uvindex'][$key],
                        'relativehumidity' => $data['data_1h']['relativehumidity'][$key],
                        'sealevelpressure' => $data['data_1h']['sealevelpressure'][$key],
                        'winddirection' => $data['data_1h']['winddirection'][$key],
                    ]
                );
            }

            return $data;
        } catch (InvalidArgumentException $error) {
            $this->logger->error($error->getMessage(), ['exception' => $error]);
            return null;
        }
    }
    
}