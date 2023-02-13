<?php

namespace App\Tests\Controller\Api;

use App\Entity\Weather;
use App\Repository\UserRepository;
use App\Repository\WeatherRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class WeatherControllerTest extends WebTestCase
{
    public function testSuccess(): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();

        $weather = new Weather();
        $weather->setCityId(1);
        $weather->setCelsuisDegrees(3);
        $weather->setWindSpeedMetrics(16);

        $weatherRepository = $this->createMock(WeatherRepository::class);

        $weatherRepository
            ->method('getCurrentDayWeatherByCity')
            ->willReturn($weather);

        $objectManager = $this->createMock(WeatherRepository::class);

        $objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($weatherRepository);


        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('johndoe@test.com');
        $client->loginUser($testUser);

        $client->request('GET', '/api/weather/Riga/today');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            "city" => "Riga",
            "temperature" => 3,
            "wind" => 16
        ]);
    }
}
