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
    #[ORM\OneToOne( inversedBy: 'userDetails' , targetEntity: User::class)]
    #[ORM\JoinColumn( nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 25)]
    private ?string $rol = null;

    #[ORM\Column]
    private ?int $varsta = null;


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




}
