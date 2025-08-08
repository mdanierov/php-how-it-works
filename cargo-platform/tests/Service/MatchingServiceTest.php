<?php

namespace App\Tests\Service;

use App\Entity\CourierProfile;
use App\Entity\Order;
use App\Service\MatchingService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class MatchingServiceTest extends TestCase
{
    public function testAssignBestCourier(): void
    {
        $c1 = (new CourierProfile())
            ->setCapacityKg(10)
            ->setRoutes([[ 'from' => 'RU', 'to' => 'DE' ]])
            ->setTravelDates([[ 'from' => date('Y-m-d'), 'to' => date('Y-m-d') ]])
            ->setRating(4.5)
            ->setReliabilityScore(0.9);

        $c2 = (new CourierProfile())
            ->setCapacityKg(10)
            ->setRoutes([[ 'from' => 'RU', 'to' => 'DE' ]])
            ->setTravelDates([[ 'from' => date('Y-m-d'), 'to' => date('Y-m-d') ]])
            ->setRating(4.9)
            ->setReliabilityScore(0.8);

        $repo = new class($c1, $c2) {
            public function __construct(private $a, private $b) {}
            public function createQueryBuilder($alias) { return $this; }
            public function andWhere($x) { return $this; }
            public function setParameter($k, $v) { return $this; }
            public function getQuery() { return $this; }
            public function getResult() { return [$this->a, $this->b]; }
        };

        $em = $this->createMock(EntityManagerInterface::class);
        $em->method('getRepository')->willReturn($repo);

        $service = new MatchingService($em);

        $order = (new Order())
            ->setWeightKg(5)
            ->setDirection('RU-DE')
            ->setFromAddress('a')
            ->setToAddress('b')
            ->setPrice('10.00')
            ->setCurrency('EUR');

        $best = $service->assignBestCourier($order);
        $this->assertNotNull($best);
        $this->assertSame(4.9, $best->getRating());
    }
}