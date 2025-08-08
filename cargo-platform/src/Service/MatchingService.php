<?php

namespace App\Service;

use App\Entity\CourierProfile;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;

class MatchingService
{
    public function __construct(private readonly EntityManagerInterface $entityManager) {}

    public function assignBestCourier(Order $order, ?\DateTimeInterface $desiredDate = null): ?CourierProfile
    {
        $desiredDate ??= new \DateTimeImmutable();

        $qb = $this->entityManager->getRepository(CourierProfile::class)->createQueryBuilder('c');
        $profiles = $qb
            ->andWhere('c.capacityKg >= :weight')
            ->setParameter('weight', $order->getWeightKg())
            ->getQuery()
            ->getResult();

        $direction = $order->getDirection();
        $eligible = array_filter($profiles, function (CourierProfile $c) use ($direction, $desiredDate) {
            $routes = $c->getRoutes() ?? [];
            $dates = $c->getTravelDates() ?? [];
            $routeOk = false;
            foreach ($routes as $r) {
                if (!isset($r['from'], $r['to'])) continue;
                $dir = sprintf('%s-%s', $r['from'], $r['to']);
                if ($dir === $direction) { $routeOk = true; break; }
            }
            $dateOk = false;
            foreach ($dates as $d) {
                if (isset($d['date'])) {
                    if ($desiredDate->format('Y-m-d') === $d['date']) { $dateOk = true; break; }
                } elseif (isset($d['from'], $d['to'])) {
                    $from = new \DateTimeImmutable($d['from']);
                    $to = new \DateTimeImmutable($d['to']);
                    if ($desiredDate >= $from && $desiredDate <= $to) { $dateOk = true; break; }
                }
            }
            return $routeOk && $dateOk;
        });

        usort($eligible, function (CourierProfile $a, CourierProfile $b) {
            $scoreA = ($a->getRating() * 0.7) + ($a->getReliabilityScore() * 0.3);
            $scoreB = ($b->getRating() * 0.7) + ($b->getReliabilityScore() * 0.3);
            return $scoreB <=> $scoreA;
        });

        return $eligible[0] ?? null;
    }
}