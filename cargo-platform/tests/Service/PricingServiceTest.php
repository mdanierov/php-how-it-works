<?php

namespace App\Tests\Service;

use App\Entity\PriceRule;
use App\Service\PricingService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class PricingServiceTest extends TestCase
{
    public function testCalculatePriceSelectsCorrectRule(): void
    {
        $rule = (new PriceRule())
            ->setMinWeightKg(1)
            ->setMaxWeightKg(5)
            ->setPrice('19.99')
            ->setCurrency('EUR')
            ->setDirection('RU-DE')
            ->setActive(true);

        $repo = new class($rule) {
            public function __construct(private $rule) {}
            public function createQueryBuilder($alias) { return $this; }
            public function andWhere($x) { return $this; }
            public function setParameters($p) { return $this; }
            public function setMaxResults($n) { return $this; }
            public function getQuery() { return $this; }
            public function getOneOrNullResult() { return $this->rule; }
        };

        $em = $this->createMock(EntityManagerInterface::class);
        $em->method('getRepository')->willReturn($repo);

        $service = new PricingService($em, 'EUR');
        $price = $service->calculatePrice(3, 'RU-DE');
        $this->assertSame('19.99', $price['amount']);
        $this->assertSame('EUR', $price['currency']);
    }
}