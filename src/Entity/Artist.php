<?php

namespace App\Entity;

use App\Repository\ArtistRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: ArtistRepository::class)]
class Artist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'artist', targetEntity: FestivalArtist::class, orphanRemoval: true)]
    private Collection $festivals;
    public function __construct()
    {
    //trebuie initializata colectia, altfel eroare pt /artist
        $this->festivals = new ArrayCollection();
    }
    #[ORM\Column(length: 30)]
    private ?string $nume = null;

    #[ORM\Column(length: 30)]
    private ?string $gen_muzical = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNume(): ?string
    {
        return $this->nume;
    }

    public function setNume(string $nume): static
    {
        $this->nume = $nume;

        return $this;
    }

    public function getGenMuzical(): ?string
    {
        return $this->gen_muzical;
    }

    public function setGenMuzical(string $gen_muzical): static
    {
        $this->gen_muzical = $gen_muzical;

        return $this;
    }

    public function getFestivals(): Collection
    {
        return $this->festivals;
    }
}
