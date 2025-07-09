<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\OneToOne(targetEntity: UserDetails::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?UserDetails $userDetails = null;

    #[ORM\OneToMany(targetEntity: Purchase::class, mappedBy: 'user')]
    private Collection $purchases;
    #[ORM\Column(type: 'boolean')]
    private bool $isVerified = false;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function __construct()
    {
        $this->purchases = new ArrayCollection();
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getUserDetails(): ?UserDetails
    {
        return $this->userDetails;
    }

    public function setUserDetails(?UserDetails $userDetails): static
    {

        if (null === $userDetails && null !== $this->userDetails) {
            $this->userDetails->setUser(null);
        }

        if (null !== $userDetails && $userDetails->getUser() !== $this) {
            $userDetails->setUser($this);
        }

        $this->userDetails = $userDetails;

        return $this;
    }
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = [];

        if ($this->userDetails && $this->userDetails->getRol()) {
            $dbRole = strtoupper($this->userDetails->getRol());

            $roles[] = str_starts_with($dbRole, 'ROLE_') ? $dbRole : 'ROLE_' . $dbRole;
        }

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }


    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self        //static
    {
        $this->isVerified = $isVerified;

        return $this;
    }
    public function getPurchases(): Collection
    {
        return $this->purchases;
    }

    public function addPurchase(Purchase $purchase): static
    {
        if (!$this->purchases->contains($purchase)) {
            $this->purchases->add($purchase);
            $purchase->setUser($this);
        }
        return $this;
    }

    public function removePurchase(Purchase $purchase): static
    {
        if ($this->purchases->contains($purchase)) {
            $this->purchases->removeElement($purchase);
            $purchase->setUser(null);
        }
        return  $this;
    }

    public function eraseCredentials(): void
    {

    }
    public function __toString(): string
    {
        return $this->name ?? '';  // for the error->object of class Proxies\__CG__\App\Entity\User could not be converted to string
    }

}

