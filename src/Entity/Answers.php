<?php

namespace App\Entity;

use App\Repository\AnswersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnswersRepository::class)]
class Answers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Question::class, inversedBy: 'answers')]
    private $id_question;

    #[ORM\Column(type: 'text')]
    private $Label;

    #[ORM\Column(type: 'boolean')]
    private $isTrue;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdQuestion(): ?question
    {
        return $this->id_question;
    }

    public function setIdQuestion(?question $id_question): self
    {
        $this->id_question = $id_question;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->Label;
    }

    public function setLabel(string $Label): self
    {
        $this->Label = $Label;

        return $this;
    }

    public function isIsTrue(): ?bool
    {
        return $this->isTrue;
    }

    public function setIsTrue(bool $isTrue): self
    {
        $this->isTrue = $isTrue;

        return $this;
    }
}