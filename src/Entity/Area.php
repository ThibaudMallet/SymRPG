<?php

namespace App\Entity;

use App\Repository\AreaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AreaRepository::class)]
#[UniqueEntity('name')]
class Area
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank()]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'area', targetEntity: Bestiary::class, orphanRemoval: true)]
    private Collection $bestiary;

    public function __construct()
    {
        $this->bestiary = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Bestiary>
     */
    public function getBestiary(): Collection
    {
        return $this->bestiary;
    }

    public function addBestiary(Bestiary $bestiary): self
    {
        if (!$this->bestiary->contains($bestiary)) {
            $this->bestiary->add($bestiary);
            $bestiary->setArea($this);
        }

        return $this;
    }

    public function removeBestiary(Bestiary $bestiary): self
    {
        if ($this->bestiary->removeElement($bestiary)) {
            // set the owning side to null (unless already changed)
            if ($bestiary->getArea() === $this) {
                $bestiary->setArea(null);
            }
        }

        return $this;
    }
}
