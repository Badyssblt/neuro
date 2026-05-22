<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ConceptsRepository;
use App\Traits\CreatedAtTrait as TraitsCreatedAtTrait;
use App\Traits\IdTrait;
use App\Traits\TitleTrait;
use App\Traits\UpdatedAtTrait;
use App\Traits\CreatedAtTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;


#[ORM\Entity(repositoryClass: ConceptsRepository::class)]
#[ApiResource(normalizationContext: ['groups' => ['concept:read', 'common']])]
class Concepts
{
    use IdTrait;
    use TitleTrait;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\ManyToOne(inversedBy: 'concepts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Modules $module = null;


    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['learning_path:read', 'concept:read'])]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    #[Groups(['learning_path:read', 'concept:read'])]
    private ?string $difficulty = null;

    #[ORM\Column]
    #[Groups(['learning_path:read', 'concept:read'])]
    private ?int $position = null;

    #[ORM\Column]
    #[Groups(['learning_path:read', 'concept:read'])]
    private ?int $importance_score = null;

    #[ORM\Column]
    #[Groups(['learning_path:read', 'concept:read'])]
    private ?int $estimated_time = null;



    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    /**
     * @var Collection<int, Lessons>
     */
    #[ORM\OneToMany(targetEntity: Lessons::class, mappedBy: 'concept')]
    #[Groups(['concept:read'])]
    private Collection $lessons;

    public function __construct()
    {
        $this->lessons = new ArrayCollection();
    }


    public function getModule(): ?Modules
    {
        return $this->module;
    }

    public function setModule(?Modules $module): static
    {
        $this->module = $module;

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

    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    public function setDifficulty(string $difficulty): static
    {
        $this->difficulty = $difficulty;

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

    public function getImportanceScore(): ?int
    {
        return $this->importance_score;
    }

    public function setImportanceScore(int $importance_score): static
    {
        $this->importance_score = $importance_score;

        return $this;
    }

    public function getEstimatedTime(): ?int
    {
        return $this->estimated_time;
    }

    public function setEstimatedTime(int $estimated_time): static
    {
        $this->estimated_time = $estimated_time;

        return $this;
    }


    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection<int, Lessons>
     */
    public function getLessons(): Collection
    {
        return $this->lessons;
    }

    public function addLesson(Lessons $lesson): static
    {
        if (!$this->lessons->contains($lesson)) {
            $this->lessons->add($lesson);
            $lesson->setConcept($this);
        }

        return $this;
    }

    public function removeLesson(Lessons $lesson): static
    {
        if ($this->lessons->removeElement($lesson)) {
            // set the owning side to null (unless already changed)
            if ($lesson->getConcept() === $this) {
                $lesson->setConcept(null);
            }
        }

        return $this;
    }

    #[Groups(['learning_path:read'])]
    public function getLessonsTotal(): int
    {
        return $this->lessons->count();
    }

    #[Groups(['learning_path:read'])]
    public function getLessonsCompleted(): int
    {
        $count = 0;
        foreach ($this->lessons as $lesson) {
            if ($lesson->isCompleted()) {
                $count++;
            }
        }

        return $count;
    }
}
