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

class WeatherController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $doctrine,
        private string $weatherstackAccessKey
    ) {}

    #[Route('/api/weather/{cityName}/today', name: 'app_weather_today')]
    public function index(string $cityName): Response
    {
        /** @var CityRepository $cityRepository */
        $cityRepository = $this->doctrine->getRepository(City::class);
        $city = $cityRepository->findOneByName($cityName);

        if (empty($city)) {
            return new Response('City not found', Response::HTTP_NOT_FOUND);
        }

        /** @var WeatherRepository $weatherRepository */
        $weatherRepository = $this->doctrine->getRepository(Weather::class);
        $weather = $weatherRepository->getCurrentDayWeatherByCity($city->getId());

        if (empty($weather)) {
            $forecastService = new WeatherStackService(
                new CurlHttpClient(),
                $this->weatherstackAccessKey
            );

            $weather = $forecastService->getCurrent($city);

            $this->doctrine->getManager()->persist($weather);
            $this->doctrine->getManager()->flush();
        }

        return new JsonResponse([
            'city' => $cityName,
            'temperature' => $weather->getCelsuisDegrees() ?? $weather->getFarenheitDegrees(),
            'wind' => $weather->getWindSpeedMetrics() ?? $weather->getWindSpeedImperial(),
        ]);
    }
}
