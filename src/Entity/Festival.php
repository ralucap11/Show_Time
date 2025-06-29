<?php

namespace App\Entity;

use App\Repository\FestivalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;

#[ORM\Entity(repositoryClass: FestivalRepository::class)]
class Festival
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nume = null;

    #[ORM\Column(length: 255)]
    private ?string $locatie = null;

    #[ORM\Column(type: 'datetime')]
    private ?DateTimeInterface $start_date = null;

    #[ORM\Column(type: 'datetime')]
    private ?DateTimeInterface $end_date = null;

    #[ORM\Column]
    private ?int $price = null;


    #[ORM\OneToMany(mappedBy: 'festival', targetEntity: FestivalArtist::class, orphanRemoval: true)]
    private Collection $festivalArtists;

    public function __construct()
    {
        $this->festivalArtists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNume(): ?string
    {
        return $this->nume;
    }

    public function setNume(?string $nume): static
    {
        $this->nume = $nume;
        return $this;
    }

    public function getLocatie(): ?string
    {
        return $this->locatie;
    }

    public function setLocatie(string $locatie): static
    {
        $this->locatie = $locatie;
        return $this;
    }

    public function getStartDate(): ?DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(?DateTimeInterface $start_date): static
    {
        $this->start_date = $start_date;
        return $this;
    }

    public function getEndDate(): ?DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(?DateTimeInterface $end_date): static
    {
        $this->end_date = $end_date;
        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;
        return $this;
    }


    public function getFestivalArtists(): Collection
    {
        return $this->festivalArtists;
    }

}
