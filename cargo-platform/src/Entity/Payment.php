<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="payments")
 */
class Payment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /** @ORM\OneToOne(targetEntity="Order") */
    private Order $order;

    /** @ORM\Column(type="string", length=64) */
    private string $paypalId;

    /** @ORM\Column(type="string", length=32) */
    private string $status;

    /** @ORM\Column(type="decimal", precision=10, scale=2) */
    private string $amount;

    /** @ORM\Column(type="string", length=3) */
    private string $currency = 'EUR';

    /** @ORM\Column(type="datetime") */
    private \DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getOrder(): Order { return $this->order; }
    public function setOrder(Order $order): self { $this->order = $order; return $this; }
    public function getPaypalId(): string { return $this->paypalId; }
    public function setPaypalId(string $id): self { $this->paypalId = $id; return $this; }
    public function getStatus(): string { return $this->status; }
    public function setStatus(string $s): self { $this->status = $s; return $this; }
    public function getAmount(): string { return $this->amount; }
    public function setAmount(string $a): self { $this->amount = $a; return $this; }
    public function getCurrency(): string { return $this->currency; }
    public function setCurrency(string $c): self { $this->currency = $c; return $this; }
    public function getCreatedAt(): \DateTimeInterface { return $this->createdAt; }
}