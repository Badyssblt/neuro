<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ModulesRepository;
use App\Traits\CreatedAtTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\IdTrait;
use App\Traits\TitleTrait;
use App\Traits\UpdatedAtTrait;

#[ORM\Entity(repositoryClass: ModulesRepository::class)]
#[ApiResource()]
class Modules
{
    use IdTrait;
    use TitleTrait;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\ManyToOne(inversedBy: 'modules')]
    #[ORM\JoinColumn(nullable: false)]
    private ?learningPath $learningPath = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $position = null;

    #[ORM\Column(length: 50)]
    private ?string $difficulty = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $estimated_duration = null;


    /**
     * @var Collection<int, Concepts>
     */
    #[ORM\OneToMany(targetEntity: Concepts::class, mappedBy: 'module', orphanRemoval: true)]
    private Collection $concepts;

    public function __construct()
    {
        $this->concepts = new ArrayCollection();
    }



    public function getLearningPath(): ?learningPath
    {
        return $this->learningPath;
    }

    public function setLearningPath(?learningPath $learningPath): static
    {
        $this->learningPath = $learningPath;

        return $this;
    }


    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    public function setDifficulty(string $difficulty): static
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getEstimatedDuration(): ?string
    {
        return $this->estimated_duration;
    }

    public function setEstimatedDuration(?string $estimated_duration): static
    {
        $this->estimated_duration = $estimated_duration;

        return $this;
    }


    /**
     * @return Collection<int, Concepts>
     */
    public function getConcepts(): Collection
    {
        return $this->concepts;
    }

    public function addConcept(Concepts $concept): static
    {
        if (!$this->concepts->contains($concept)) {
            $this->concepts->add($concept);
            $concept->setModule($this);
        }

        return $this;
    }

    public function removeConcept(Concepts $concept): static
    {
        if ($this->concepts->removeElement($concept)) {
            // set the owning side to null (unless already changed)
            if ($concept->getModule() === $this) {
                $concept->setModule(null);
            }
        }

        return $this;
    }
}
