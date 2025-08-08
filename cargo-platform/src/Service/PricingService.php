<?php

namespace App\Service;

use App\Entity\PriceRule;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;

class PricingService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly string $defaultCurrency = 'EUR'
    ) {}

    public function calculatePrice(int $weightKg, string $direction): array
    {
        $repo = $this->entityManager->getRepository(PriceRule::class);
        $rule = $repo->createQueryBuilder('r')
            ->andWhere('r.active = true')
            ->andWhere('r.direction = :direction')
            ->andWhere(':weight BETWEEN r.minWeightKg AND r.maxWeightKg')
            ->setParameters(['direction' => $direction, 'weight' => $weightKg])
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$rule) {
            throw new InvalidArgumentException('No price rule found for this weight and direction');
        }

        return [
            'amount' => (string) $rule->getPrice(),
            'currency' => $rule->getCurrency() ?: $this->defaultCurrency,
        ];
    }
}