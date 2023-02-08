<?php

namespace App\Tests\Service\WeatherStackService;

use App\Entity\City;
use App\Entity\Weather;
use App\Repository\CityRepository;
use App\Service\WeatherStackService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class GetCurrentTest extends WebTestCase
{
    public function testSuccess(): void
    {
        $mockResponseData = [
            "request" => [
                "type" => "City",
                "query" => "Riga, Latvia",
                "language" => "en",
                "unit" => "m",
            ],
            "location" => [
                "name" => "Riga",
                "country" => "Latvia",
                "region" => "Riga",
                "lat" => "56.950",
                "lon" => "24.100",
                "timezone_id" => "Europe/Riga",
                "localtime" => "2023-02-06 18:10",
                "localtime_epoch" => 1675707000,
                "utc_offset" => "2.0",
            ],
            "current" => [
                "observation_time" => "04:10 PM",
                "temperature" => 2,
                "weather_code" => 116,
                "weather_icons" => [
                    0 => "https://cdn.worldweatheronline.com/images/wsymbols01_png_64/wsymbol_0004_black_low_cloud.png",
                ],
                "weather_descriptions" => [
                    0 => "Partly cloudy",
                ],
                "wind_speed" => 17,
                "wind_degree" => 330,
                "wind_dir" => "NNW",
                "pressure" => 1034,
                "precip" => 0,
                "humidity" => 87,
                "cloudcover" => 50,
                "feelslike" => -2,
                "uv_index" => 1,
                "visibility" => 10,
                "is_day" => "no",
            ]
        ];
        $mockResponseJson = json_encode($mockResponseData, JSON_THROW_ON_ERROR);
        $mockResponse = new MockResponse($mockResponseJson, [
            'http_code' => 201,
            'response_headers' => ['Content-Type: application/json'],
        ]);

        $httpClient = new MockHttpClient($mockResponse, 'http://api.weatherstack.com/');
        $service = new WeatherStackService(
            $httpClient,
            'dummyKey'
        );

        /** @var $cityRepository $cityRepository */
        $cityRepository = static::getContainer()->get(CityRepository::class);
        $testCity = $cityRepository->findOneByName('Riga');
        // Act
        $responseData = $service->getCurrent($testCity);

        // Assert
        self::assertSame('GET', $mockResponse->getRequestMethod());
        self::assertStringContainsString('http://api.weatherstack.com/current', $mockResponse->getRequestUrl());

        self::assertSame($responseData->getCelsuisDegrees(), 2);
        self::assertSame($responseData->getWindSpeedMetrics(), 17);
    }
}