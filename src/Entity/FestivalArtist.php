<?php

namespace App\Entity;

use App\Repository\FestivalArtistRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FestivalArtistRepository::class)]
class FestivalArtist
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Festival::class, inversedBy: 'festivalArtists')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Festival $festival = null;

    #[ORM\ManyToOne(targetEntity: Artist::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Artist $artist = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFestival(): ?Festival
    {
        return $this->festival;
    }

    public function setFestival(?Festival $festival): static
    {
        $this->festival = $festival;

        return $this;
    }

    public function getArtist(): ?Artist
    {
        return $this->artist;
    }

    public function setArtist(?Artist $artist): static
    {
        $this->artist = $artist;

        return $this;
    }
}
