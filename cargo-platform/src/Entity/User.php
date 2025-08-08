<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="users")
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private string $email;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @ORM\Column(type="string")
     */
    private string $password;

    /** @ORM\Column(type="string", length=100, nullable=true) */
    private ?string $firstName = null;

    /** @ORM\Column(type="string", length=100, nullable=true) */
    private ?string $lastName = null;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    private ?string $phone = null;

    /** @ORM\Column(type="string", length=2, nullable=true) */
    private ?string $country = null;

    /** @ORM\Column(type="datetime", nullable=true) */
    private ?\DateTimeInterface $verifiedAt = null;

    /** @ORM\Column(type="datetime") */
    private \DateTimeInterface $createdAt;

    /** @ORM\Column(type="datetime") */
    private \DateTimeInterface $updatedAt;

    /**
     * @ORM\OneToOne(targetEntity="CourierProfile", mappedBy="user", cascade={"persist", "remove"})
     */
    private ?CourierProfile $courierProfile = null;

    public function __construct()
    {
        $this->roles = ['ROLE_CLIENT'];
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    /** @ORM\PreUpdate */
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getUserIdentifier(): string { return $this->email; }
    public function getUsername(): string { return $this->email; }
    public function setEmail(string $email): self { $this->email = $email; return $this; }
    public function getEmail(): string { return $this->email; }

    public function getRoles(): array
    {
        $roles = $this->roles;
        if (!in_array('ROLE_USER', $roles, true)) {
            $roles[] = 'ROLE_USER';
        }
        return array_values(array_unique($roles));
    }

    public function setRoles(array $roles): self { $this->roles = $roles; return $this; }

    public function getPassword(): string { return $this->password; }
    public function setPassword(string $password): self { $this->password = $password; return $this; }

    public function eraseCredentials(): void {}

    public function getFirstName(): ?string { return $this->firstName; }
    public function setFirstName(?string $firstName): self { $this->firstName = $firstName; return $this; }
    public function getLastName(): ?string { return $this->lastName; }
    public function setLastName(?string $lastName): self { $this->lastName = $lastName; return $this; }
    public function getPhone(): ?string { return $this->phone; }
    public function setPhone(?string $phone): self { $this->phone = $phone; return $this; }
    public function getCountry(): ?string { return $this->country; }
    public function setCountry(?string $country): self { $this->country = $country; return $this; }

    public function getVerifiedAt(): ?\DateTimeInterface { return $this->verifiedAt; }
    public function setVerifiedAt(?\DateTimeInterface $verifiedAt): self { $this->verifiedAt = $verifiedAt; return $this; }

    public function getCreatedAt(): \DateTimeInterface { return $this->createdAt; }
    public function getUpdatedAt(): \DateTimeInterface { return $this->updatedAt; }

    public function getCourierProfile(): ?CourierProfile { return $this->courierProfile; }
    public function setCourierProfile(?CourierProfile $courierProfile): self { $this->courierProfile = $courierProfile; return $this; }
}