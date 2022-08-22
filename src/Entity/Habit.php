<?php

namespace App\Entity;

use App\Repository\HabitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HabitRepository::class)]
class Habit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $Name;

    #[ORM\Column(type: 'float')]
    private $TimeSpent;

    #[ORM\Column(type: 'datetime')]
    private $TimeStart;

    #[ORM\Column(type: 'datetime')]
    private $TimeEnd;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'habits')]
    #[ORM\JoinColumn(nullable: false)]
    private $UserId;

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

    public function getTimeSpent(): ?float
    {
        return $this->TimeSpent;
    }

    public function setTimeSpent(float $TimeSpent): self
    {
        $this->TimeSpent = $TimeSpent;

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

    public function getTimeEnd(): ?\DateTimeInterface
    {
        return $this->TimeEnd;
    }

    public function setTimeEnd(\DateTimeInterface $TimeEnd): self
    {
        $this->TimeEnd = $TimeEnd;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->UserId;
    }

    public function setUserId(?User $UserId): self
    {
        $this->UserId = $UserId;

        return $this;
    }

//    public function addd
}
