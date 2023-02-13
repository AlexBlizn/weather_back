<?php

namespace App\Service;

use App\Entity\City;
use App\Entity\Weather;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherStackService
{
    private const SERVICE_NAME = 'weatherstack';

    public function __construct(
        private HttpClientInterface $client,
        private string $weatherstackAccessKey
    )
    {}

    public function getCurrent(City $city): Weather
    {
        $response = $this->makeRequest(
            urlPath: 'current',
            options: [
                'query' => [
                    'query' => $city->getName(),
                    'units' => $city->getUnits(),
                ],
            ],
        );

        $weather = new Weather();
        $weather->setCityId(city_id: $city->getId());
        switch ($response['request']['unit']) {
            case 'm':
                $weather->setCelsuisDegrees($response['current']['temperature']);
                $weather->setWindSpeedMetrics($response['current']['wind_speed']);
                break;
            case 'f':
                $weather->setFarenheitDegrees($response['current']['temperature']);
                $weather->setWindSpeedImperial($response['current']['wind_speed']);
                break;
            default:
        }
        $weather->setSource(self::SERVICE_NAME);

        return $weather;
    }

    private function makeRequest(string $urlPath, array $options): array
    {
        $options['query']['access_key'] = $this->weatherstackAccessKey;

        $response = $this->client->request(
            'GET',
            'http://api.weatherstack.com/' . $urlPath,
            $options
        );

        $content = $response->toArray();

        return $content;
    }
}