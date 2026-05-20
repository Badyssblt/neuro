<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\LearningGoalRepository;
use App\Traits\CreatedAtTrait;
use App\Traits\IdTrait;
use App\Traits\TitleTrait;
use App\Traits\UpdatedAtTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource()]
#[ORM\Entity(repositoryClass: LearningGoalRepository::class)]
class LearningGoal
{
    use IdTrait;
    use TitleTrait;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    private ?string $level = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\ManyToOne(inversedBy: 'learningGoals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $learner = null;

    /**
     * @var Collection<int, LearningPath>
     */
    #[ORM\OneToMany(targetEntity: LearningPath::class, mappedBy: 'goal')]
    private Collection $learningPaths;

    public function __construct()
    {
        $this->learningPaths = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
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

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(string $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }


    public function getLearner(): ?User
    {
        return $this->learner;
    }

    public function setLearner(?User $learner): static
    {
        $this->learner = $learner;

        return $this;
    }

    /**
     * @return Collection<int, LearningPath>
     */
    public function getLearningPaths(): Collection
    {
        return $this->learningPaths;
    }

    public function addLearningPath(LearningPath $learningPath): static
    {
        if (!$this->learningPaths->contains($learningPath)) {
            $this->learningPaths->add($learningPath);
            $learningPath->setGoal($this);
        }

        return $this;
    }

    public function removeLearningPath(LearningPath $learningPath): static
    {
        if ($this->learningPaths->removeElement($learningPath)) {
            // set the owning side to null (unless already changed)
            if ($learningPath->getGoal() === $this) {
                $learningPath->setGoal(null);
            }
        }

        return $this;
    }
}
