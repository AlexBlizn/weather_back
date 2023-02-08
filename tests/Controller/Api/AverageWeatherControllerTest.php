<?php

namespace App\Tests\Controller\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class AverageWeatherControllerTest extends ApiTestCase
{
    public function testSomething(): void
    {
        $response = static::createClient()->request('GET', '/api/weather/Riga/week');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            "city" => "Riga",
            "temperature" => 1,
            "wind" => 9
        ]);
    }
}
