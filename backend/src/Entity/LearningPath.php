<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\LearningPathRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\IdTrait;
use App\Traits\TitleTrait;
use App\Traits\UpdatedAtTrait;
use App\Traits\CreatedAtTrait;


#[ORM\Entity(repositoryClass: LearningPathRepository::class)]
#[ApiResource()]
class LearningPath
{
    use IdTrait;
    use TitleTrait;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\ManyToOne(inversedBy: 'learningPaths')]
    private ?LearningGoal $goal = null;


    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $estimated_duration = null;


    /**
     * @var Collection<int, Modules>
     */
    #[ORM\OneToMany(targetEntity: Modules::class, mappedBy: 'learningPath', orphanRemoval: true)]
    private Collection $modules;

    #[ORM\Column]
    private ?bool $completed = null;

    public function __construct()
    {
        $this->modules = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
        $this->updated_at = new \DateTimeImmutable();
    }


    public function getGoal(): ?LearningGoal
    {
        return $this->goal;
    }

    public function setGoal(?LearningGoal $goal): static
    {
        $this->goal = $goal;

        return $this;
    }


    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getEstimatedDuration(): ?int
    {
        return $this->estimated_duration;
    }

    public function setEstimatedDuration(int $estimated_duration): static
    {
        $this->estimated_duration = $estimated_duration;

        return $this;
    }


    /**
     * @return Collection<int, Modules>
     */
    public function getModules(): Collection
    {
        return $this->modules;
    }

    public function addModule(Modules $module): static
    {
        if (!$this->modules->contains($module)) {
            $this->modules->add($module);
            $module->setLearningPath($this);
        }

        return $this;
    }

    public function removeModule(Modules $module): static
    {
        if ($this->modules->removeElement($module)) {
            // set the owning side to null (unless already changed)
            if ($module->getLearningPath() === $this) {
                $module->setLearningPath(null);
            }
        }

        return $this;
    }

    public function isCompleted(): ?bool
    {
        return $this->completed;
    }

    public function setCompleted(bool $completed): static
    {
        $this->completed = $completed;

        return $this;
    }
}
