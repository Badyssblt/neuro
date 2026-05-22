<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\LessonsRepository;
use App\Traits\CreatedAtTrait;
use App\Traits\IdTrait;
use App\Traits\TitleTrait;
use App\Traits\UpdatedAtTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: LessonsRepository::class)]
#[ApiResource(normalizationContext: ['groups' => ['lesson:read', 'common']])]
class Lessons
{
    use IdTrait;
    use TitleTrait;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['lesson:read'])]
    private ?string $content = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['concept:read', 'lesson:read'])]
    private ?string $summary = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['lesson:read'])]
    private ?string $examples = null;

    #[ORM\Column]
    #[Groups(['concept:read', 'lesson:read'])]
    private ?int $position = null;

    #[ORM\Column(options: ['default' => false])]
    #[Groups(['concept:read', 'lesson:read'])]
    private bool $completed = false;

    #[ORM\ManyToOne(inversedBy: 'lessons')]
    private ?Concepts $concept = null;



    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): static
    {
        $this->summary = $summary;

        return $this;
    }

    public function getExamples(): ?string
    {
        return $this->examples;
    }

    public function setExamples(string $examples): static
    {
        $this->examples = $examples;

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

    public function isCompleted(): bool
    {
        return $this->completed;
    }

    public function setCompleted(bool $completed): static
    {
        $this->completed = $completed;

        return $this;
    }

    public function getConcept(): ?Concepts
    {
        return $this->concept;
    }

    public function setConcept(?Concepts $concept): static
    {
        $this->concept = $concept;

        return $this;
    }
}
