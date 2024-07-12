<?php
use PHPUnit\Framework\TestCase;
use App\Services\WeatherService;

class WeatherForecastTest extends TestCase {
    public function testWeatherForecastCreation() {
        $logger = require __DIR__ . '/../config/logger.php';
        $weather = new WeatherService($logger,-0.895092,116.719437,'json');
        $data = $weather->getWeather();
        $this->assertArrayHasKey('time', $data);
    }
}