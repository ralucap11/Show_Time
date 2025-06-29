<?php

namespace App\Entity;

use App\Repository\PurchaseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseRepository::class)]
class Purchase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 28)]
    private ?string $user = null;

    #[ORM\Column(length: 255)]
    private ?string $festival = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(string $user): static
    {
        $this->user = $user;

        return $this;
    }


    //pentru purchase/1 purchase/id
    public function getFestival(): ?string
    {
        return $this->festival;
    }

    public function setFestival(string $festival): static
    {
        $this->festival = $festival;

        return $this;
    }
}
