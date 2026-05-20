<?php

namespace App\Entity;

use App\Repository\LessonsRepository;
use App\Traits\CreatedAtTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Traits\IdTrait;
use App\Traits\TitleTrait;
use App\Traits\UpdatedAtTrait;

#[ORM\Entity(repositoryClass: LessonsRepository::class)]
#[ApiResource()]
class Lessons
{
    use IdTrait;
    use TitleTrait;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $summary = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $examples = null;

    #[ORM\Column(length: 50)]
    private ?string $difficulty = null;

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

    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    public function setDifficulty(string $difficulty): static
    {
        $this->difficulty = $difficulty;

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
