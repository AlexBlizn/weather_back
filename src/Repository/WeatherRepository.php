<?php

namespace App\Repository;

use App\Entity\Weather;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Weather>
 *
 * @method Weather|null find($id, $lockMode = null, $lockVersion = null)
 * @method Weather|null findOneBy(array $criteria, array $orderBy = null)
 * @method Weather[]    findAll()
 * @method Weather[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeatherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Weather::class);
    }

    public function save(Weather $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Weather $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getCurrentDayWeatherByCity(int $cityId): ?Weather
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.city_id = :val')
            ->setParameter('val', $cityId)
            ->andWhere('w.created_at > :start')
            ->setParameter('start', date('Y-m-d 00:00:00'))
            ->andWhere('w.created_at < :end')
            ->setParameter('end', date('Y-m-d 23:59:59'))
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getLastWeekAverageWeatherByCity(int $cityId): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT 
            AVG(w.celsuis_degrees) celsuis_degrees, 
            AVG(w.farenheit_degrees) farenheit_degrees,
            AVG(w.wind_speed_metrics) wind_speed_metrics,
            AVG(w.wind_speed_imperial) wind_speed_imperial
            FROM App\Entity\Weather w
            WHERE w.city_id = :city_id AND
            w.created_at > :start'
        )->setParameter('city_id', $cityId)->setParameter('start', date('Y-m-d 00:00:00', strtotime('-1 week')));

        return $query->getResult();
    }

}
