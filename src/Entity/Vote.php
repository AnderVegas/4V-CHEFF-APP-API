<?php

namespace App\Entity;

use App\Repository\VoteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VoteRepository::class)]
class Vote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 1)]
    private ?string $rate = null;

    #[ORM\ManyToOne(inversedBy: 'votes')]
    private ?Recipe $id_recipe = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRate(): ?string
    {
        return $this->rate;
    }

    public function setRate(string $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    public function getIdRecipe(): ?Recipe
    {
        return $this->id_recipe;
    }

    public function setIdRecipe(?Recipe $id_recipe): static
    {
        $this->id_recipe = $id_recipe;

        return $this;
    }
}
