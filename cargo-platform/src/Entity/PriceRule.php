<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="price_rules")
 */
class PriceRule
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /** @ORM\Column(type="integer") */
    private int $minWeightKg = 0;

    /** @ORM\Column(type="integer") */
    private int $maxWeightKg = 0;

    /** @ORM\Column(type="decimal", precision=10, scale=2) */
    private string $price;

    /** @ORM\Column(type="string", length=3) */
    private string $currency = 'EUR';

    /** @ORM\Column(type="string", length=32) */
    private string $direction; // e.g. RU-DE

    /** @ORM\Column(type="boolean") */
    private bool $active = true;

    public function getId(): ?int { return $this->id; }
    public function getMinWeightKg(): int { return $this->minWeightKg; }
    public function setMinWeightKg(int $v): self { $this->minWeightKg = $v; return $this; }
    public function getMaxWeightKg(): int { return $this->maxWeightKg; }
    public function setMaxWeightKg(int $v): self { $this->maxWeightKg = $v; return $this; }
    public function getPrice(): string { return $this->price; }
    public function setPrice(string $p): self { $this->price = $p; return $this; }
    public function getCurrency(): string { return $this->currency; }
    public function setCurrency(string $c): self { $this->currency = $c; return $this; }
    public function getDirection(): string { return $this->direction; }
    public function setDirection(string $d): self { $this->direction = $d; return $this; }
    public function isActive(): bool { return $this->active; }
    public function setActive(bool $a): self { $this->active = $a; return $this; }
}