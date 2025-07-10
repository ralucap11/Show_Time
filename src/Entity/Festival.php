<?php

namespace App\Entity;

use App\Repository\FestivalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: FestivalRepository::class)]
#[Assert\Callback('validateDates')]

class Festival
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nume = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $locatie = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\GreaterThanOrEqual('today', message: 'Start date cannot be in the past.')]
    private ?DateTimeInterface $start_date = null;

    #[ORM\Column(type: 'datetime')]
    #[Assert\GreaterThanOrEqual('today', message: 'End date cannot be in the past.')]
    private ?DateTimeInterface $end_date = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column(name: 'numberTickets')]
    #[Assert\PositiveOrZero(message: 'Capacity must be 0 or more')]
    private ?int $numberTickets= null;

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

    public function setLocatie(?string $locatie): static
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

    public function setPrice(?int $price): static
    {
        $this->price = $price;
        return $this;
    }


    public function getFestivalArtists(): Collection
    {
        return $this->festivalArtists;
    }

    public function getNumberTickets(): ?int
    {
        return $this->numberTickets;
    }

    public function setNumberTickets(?int $numberTickets): static
    {
        $this->numberTickets = $numberTickets;
        return $this;
    }

    public function __toString(): string
    {
        return $this->nume ?? '';  //for the error->Object of class Proxies\__CG__\App\Entity\Festival could not be converted to string
    }


    public function validateDates(ExecutionContextInterface $context): void
    {
        if ($this->start_date && $this->end_date && $this->start_date >= $this->end_date) {
            $context->buildViolation('Start date must be before end date!')
                ->atPath('start_date')
                ->addViolation();
        }
    }

}
