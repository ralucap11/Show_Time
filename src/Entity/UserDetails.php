<?php

namespace App\Entity;

use App\Repository\UserDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserDetailsRepository::class)]
class UserDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
     //un user are un "detaliu"
    #[ORM\OneToOne( targetEntity: User::class, inversedBy: 'userDetails')]
    #[ORM\JoinColumn( nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 25,nullable: true)]
    private ?string $rol = null;

    #[ORM\Column]
    private ?int $varsta = null;

    #[ORM\Column(length: 28,nullable: true)]
    private ?string $name = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRol(): ?string
    {
        return $this->rol;
    }

    public function setRol(string $rol): static
    {
        $this->rol = $rol;

        return $this;
    }

    public function getVarsta(): ?int
    {
        return $this->varsta;
    }

    public function setVarsta(int $varsta): static
    {
        $this->varsta = $varsta;

        return $this;
    }


    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }




}
