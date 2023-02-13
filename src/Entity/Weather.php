<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Controller\Api\WeatherController;
use App\Repository\WeatherRepository;
use App\State\WeatherProvider;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource(operations: [
    new Get(controller: WeatherController::class)
])]
#[ORM\Entity(repositoryClass: WeatherRepository::class)]
#[Get(provider: WeatherProvider::class)]
class Weather
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $city_id = null;

    #[ORM\Column(nullable: true)]
    private ?int $celsuis_degrees = null;

    #[ORM\Column(nullable: true)]
    private ?int $farenheit_degrees = null;

    #[ORM\Column(nullable: true)]
    private ?int $wind_speed_metrics = null;

    #[ORM\Column(nullable: true)]
    private ?int $wind_speed_imperial = null;

    #[ORM\Column(length: 255)]
    private ?string $source = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    public function __construct() {
        $this->setCreatedAt(new \DateTimeImmutable());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCityId(): ?int
    {
        return $this->city_id;
    }

    public function setCityId(int $city_id): self
    {
        $this->city_id = $city_id;

        return $this;
    }

    public function getCelsuisDegrees(): ?int
    {
        return $this->celsuis_degrees;
    }

    public function setCelsuisDegrees(?int $celsuis_degrees): self
    {
        $this->celsuis_degrees = $celsuis_degrees;

        return $this;
    }

    public function getFarenheitDegrees(): ?int
    {
        return $this->farenheit_degrees;
    }

    public function setFarenheitDegrees(?int $farenheit_degrees): self
    {
        $this->farenheit_degrees = $farenheit_degrees;

        return $this;
    }

    public function getWindSpeedMetrics(): ?int
    {
        return $this->wind_speed_metrics;
    }

    public function setWindSpeedMetrics(?int $wind_speed_metrics): self
    {
        $this->wind_speed_metrics = $wind_speed_metrics;

        return $this;
    }

    public function getWindSpeedImperial(): ?int
    {
        return $this->wind_speed_imperial;
    }

    public function setWindSpeedImperial(?int $wind_speed_imperial): self
    {
        $this->wind_speed_imperial = $wind_speed_imperial;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}
