<?php

namespace App\Tests\Controller\Api;

use App\Repository\UserRepository;
use App\Repository\WeatherRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AverageWeatherControllerTest extends WebTestCase
{
    public function testSuccess(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();

        $weatherRepository = $this->createMock(WeatherRepository::class);

        $weatherRepository
            ->method('getLastWeekAverageWeatherByCity')
            ->willReturn([
                'celsuis_degrees' => 4,
                'farenheit_degrees' => null,
                'wind_speed_metrics' => 10,
                'wind_speed_imperial' => null,
            ]);

        $objectManager = $this->createMock(WeatherRepository::class);

        $objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($weatherRepository);

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('johndoe@test.com');
        $client->loginUser($testUser);

        $client->request('GET', '/api/weather/Riga/week');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            "city" => "Riga",
            "temperature" => 4,
            "wind" => 10
        ]);
    }
}
