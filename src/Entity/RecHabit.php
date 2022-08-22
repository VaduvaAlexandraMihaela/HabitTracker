<?php

namespace App\Entity;

use App\Repository\RecHabitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecHabitRepository::class)]
class RecHabit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $Name;

    #[ORM\Column(type: 'float')]
    private $Duration;

    #[ORM\Column(type: 'datetime')]
    private $TimeStart;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getDuration(): ?float
    {
        return $this->Duration;
    }

    public function setDuration(float $Duration): self
    {
        $this->Duration = $Duration;

        return $this;
    }

    public function getTimeStart(): ?\DateTimeInterface
    {
        return $this->TimeStart;
    }

    public function setTimeStart(\DateTimeInterface $TimeStart): self
    {
        $this->TimeStart = $TimeStart;

        return $this;
    }
}
