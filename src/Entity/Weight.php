<?php

namespace App\Entity;

use App\Repository\WeightRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WeightRepository::class)]
class Weight
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $Value;

    #[ORM\Column(type: 'integer')]
    private $WeightLost;

    #[ORM\Column(type: 'integer')]
    private $Goal;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'weights')]
    #[ORM\JoinColumn(nullable: false)]
    private $user_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?int
    {
        return $this->Value;
    }

    public function setValue(int $Value): self
    {
        $this->Value = $Value;

        return $this;
    }

    public function getWeightLost(): ?int
    {
        return $this->WeightLost;
    }

    public function setWeightLost(int $WeightLost): self
    {
        $this->WeightLost = $WeightLost;

        return $this;
    }

    public function getGoal(): ?int
    {
        return $this->Goal;
    }

    public function setGoal(int $Goal): self
    {
        $this->Goal = $Goal;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }
}
