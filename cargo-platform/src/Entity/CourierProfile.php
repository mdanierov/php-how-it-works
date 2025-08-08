<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity()
 * @ORM\Table(name="courier_profiles")
 * @Vich\Uploadable
 */
class CourierProfile
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="courierProfile")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private User $user;

    /** @ORM\Column(type="json", nullable=true) */
    private ?array $routes = null; // e.g. [{from: "MOW", to: "BER"}]

    /** @ORM\Column(type="json", nullable=true) */
    private ?array $travelDates = null; // array of ISO date strings or ranges

    /** @ORM\Column(type="integer") */
    private int $capacityKg = 0;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * @Vich\UploadableField(mapping="passport_images", fileNameProperty="passportPath")
     * @var File|null
     */
    private ?File $passportFile = null;

    /** @ORM\Column(type="string", length=255, nullable=true) */
    private ?string $passportPath = null;

    /** @ORM\Column(type="datetime", nullable=true) */
    private ?\DateTimeInterface $passportUploadedAt = null;

    /** @ORM\Column(type="datetime", nullable=true) */
    private ?\DateTimeInterface $passportDeletedAt = null;

    /** @ORM\Column(type="float") */
    private float $rating = 0.0;

    /** @ORM\Column(type="float") */
    private float $reliabilityScore = 0.0;

    public function getId(): ?int { return $this->id; }
    public function getUser(): User { return $this->user; }
    public function setUser(User $user): self { $this->user = $user; return $this; }

    public function getRoutes(): ?array { return $this->routes; }
    public function setRoutes(?array $routes): self { $this->routes = $routes; return $this; }

    public function getTravelDates(): ?array { return $this->travelDates; }
    public function setTravelDates(?array $travelDates): self { $this->travelDates = $travelDates; return $this; }

    public function getCapacityKg(): int { return $this->capacityKg; }
    public function setCapacityKg(int $capacityKg): self { $this->capacityKg = $capacityKg; return $this; }

    public function setPassportFile(?File $file = null): void
    {
        $this->passportFile = $file;
        if ($file) {
            $this->passportUploadedAt = new \DateTimeImmutable();
        }
    }

    public function getPassportFile(): ?File { return $this->passportFile; }

    public function getPassportPath(): ?string { return $this->passportPath; }
    public function setPassportPath(?string $passportPath): void { $this->passportPath = $passportPath; }

    public function getPassportUploadedAt(): ?\DateTimeInterface { return $this->passportUploadedAt; }
    public function setPassportUploadedAt(?\DateTimeInterface $dt): self { $this->passportUploadedAt = $dt; return $this; }

    public function getPassportDeletedAt(): ?\DateTimeInterface { return $this->passportDeletedAt; }
    public function setPassportDeletedAt(?\DateTimeInterface $dt): self { $this->passportDeletedAt = $dt; return $this; }

    public function getRating(): float { return $this->rating; }
    public function setRating(float $rating): self { $this->rating = $rating; return $this; }

    public function getReliabilityScore(): float { return $this->reliabilityScore; }
    public function setReliabilityScore(float $score): self { $this->reliabilityScore = $score; return $this; }
}