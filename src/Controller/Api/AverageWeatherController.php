<?php

namespace App\Controller\Api;

use App\Entity\City;
use App\Entity\Weather;
use App\Repository\CityRepository;
use App\Repository\WeatherRepository;
use App\Service\WeatherStackService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AverageWeatherController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $doctrine,
        private string $weatherstackAccessKey
    ) {}

    #[Route('/api/weather/{cityName}/week', name: 'app_weather_week')]
    public function index(string $cityName): Response
    {
        /** @var CityRepository $repository */
        $repository = $this->doctrine->getRepository(City::class);
        $city = $repository->findOneByName($cityName);

        if (empty($city)) {
            return new Response('City not found', Response::HTTP_NOT_FOUND);
        }

        /** @var WeatherRepository $weatherRepository */
        $weatherRepository = $this->doctrine->getRepository(Weather::class);
        $weather = $weatherRepository->getLastWeekAverageWeatherByCity(cityId: $city->getId());

        return new JsonResponse([
            'city' => $cityName,
            'temperature' => $weather[0]['celsuis_degrees'] ?? $weather[0]['farenheit_degrees'],
            'wind' => $weather[0]['wind_speed_metrics'] ?? $weather[0]['wind_speed_imperial'],
        ]);
    }
}
