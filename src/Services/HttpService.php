<?php 
namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use InvalidArgumentException;

class HttpService {
    public static function makeRequest($url): string {
        $clientOptions = [
            'verify' => false,
            'timeout' => 10,
            'http_errors' => true,
        ];

        if ($_ENV['USE_PROXY'] === "true") {
            $clientOptions['proxy'] = "http://".$_ENV['PROXY_USER'].":".$_ENV['PROXY_PASS']."@".$_ENV['PROXY_SERVER'];
        }

        $client = new Client($clientOptions);

        try {
            $response = $client->get($url);
            return $response->getBody()->getContents();
        } catch (RequestException $e) {
            throw new InvalidArgumentException("Failed to fetch data from API.".$e->getMessage());
        }
    }
    public static function curlGetContents($url): string {
        $ch = curl_init();
        if ($ch === false) {
            throw new \Exception('failed to initialize');
        }

        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if ($_ENV['USE_PROXY'] === "true") {
            $proxy = "http://".$_ENV['PROXY_SERVER'];
            $proxy_auth = $_ENV['PROXY_USER'].":". $_ENV['PROXY_PASS'];
            curl_setopt($ch, CURLOPT_PROXY, $proxy);
            curl_setopt($ch, CURLOPT_PROXYUSERPWD,$proxy_auth);
            curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
            curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_NTLM);
        }

        $response = curl_exec($ch);
        if ($response === false) {
            throw new \Exception(curl_error($ch), curl_errno($ch));
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpCode != 200) {
            throw new InvalidArgumentException("Failed to fetch data from API.");
        }
        return $response;
    }

}