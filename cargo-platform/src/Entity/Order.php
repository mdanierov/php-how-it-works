<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="orders")
 * @ORM\HasLifecycleCallbacks()
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /** @ORM\ManyToOne(targetEntity="User") */
    private User $client;

    /** @ORM\ManyToOne(targetEntity="User", nullable=true) */
    private ?User $assignedCourier = null;

    /** @ORM\Column(type="integer") */
    private int $weightKg;

    /** @ORM\Column(type="decimal", precision=10, scale=2) */
    private string $price;

    /** @ORM\Column(type="string", length=3) */
    private string $currency = 'EUR';

    /** @ORM\Column(type="string", length=255) */
    private string $fromAddress;

    /** @ORM\Column(type="string", length=255) */
    private string $toAddress;

    /** @ORM\Column(type="string", length=32) */
    private string $direction; // e.g. RU-DE

    /** @ORM\Column(type="string", length=32) */
    private string $status = 'pending';

    /** @ORM\Column(type="datetime") */
    private \DateTimeInterface $createdAt;

    /** @ORM\Column(type="datetime") */
    private \DateTimeInterface $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    /** @ORM\PreUpdate */
    public function onPreUpdate(): void { $this->updatedAt = new \DateTimeImmutable(); }

    public function getId(): ?int { return $this->id; }
    public function getClient(): User { return $this->client; }
    public function setClient(User $client): self { $this->client = $client; return $this; }
    public function getAssignedCourier(): ?User { return $this->assignedCourier; }
    public function setAssignedCourier(?User $courier): self { $this->assignedCourier = $courier; return $this; }
    public function getWeightKg(): int { return $this->weightKg; }
    public function setWeightKg(int $w): self { $this->weightKg = $w; return $this; }
    public function getPrice(): string { return $this->price; }
    public function setPrice(string $p): self { $this->price = $p; return $this; }
    public function getCurrency(): string { return $this->currency; }
    public function setCurrency(string $c): self { $this->currency = $c; return $this; }
    public function getFromAddress(): string { return $this->fromAddress; }
    public function setFromAddress(string $a): self { $this->fromAddress = $a; return $this; }
    public function getToAddress(): string { return $this->toAddress; }
    public function setToAddress(string $a): self { $this->toAddress = $a; return $this; }
    public function getDirection(): string { return $this->direction; }
    public function setDirection(string $d): self { $this->direction = $d; return $this; }
    public function getStatus(): string { return $this->status; }
    public function setStatus(string $s): self { $this->status = $s; return $this; }
    public function getCreatedAt(): \DateTimeInterface { return $this->createdAt; }
    public function getUpdatedAt(): \DateTimeInterface { return $this->updatedAt; }
}