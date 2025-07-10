<?php

namespace App\Entity;

use App\Repository\PurchaseRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchaseRepository::class)]
class Purchase
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

     #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'purchases')]
     #[ORM\JoinColumn(nullable: false)]
     private ?User $user = null;


    #[ORM\ManyToOne(targetEntity: Festival::class, inversedBy: 'purchases')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Festival $festival = null;

    #[ORM\Column(type: 'datetime')]
    private ?DateTimeInterface $purchase_date = null;


    #[ORM\Column]
    private ?int $tickets = 0;




    public function getId(): ?int
    {
        return $this->id;
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

    public function getPurchaseDate(): ?DateTimeInterface
    {
        return $this->purchase_date;
    }

    public function setPurchaseDate(DateTimeInterface $purchase_date): static
    {
        $this->purchase_date = $purchase_date;
        return $this;
    }



    //pentru purchase/1 purchase/id
    public function getFestival(): ?Festival
    {
        return $this->festival;
    }

    public function setFestival(?Festival $festival): static
    {
        $this->festival = $festival;

        return $this;
    }

    private function getTickets(): int
    {
       return $this->tickets;
    }

    public function setTickets(int $tickets): static
    {
        $this->tickets = $tickets;
        return $this;
    }


}
