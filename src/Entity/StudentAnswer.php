<?php

namespace App\Entity;

use App\Repository\StudentAnswerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentAnswerRepository::class)]
class StudentAnswer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\OneToOne(targetEntity: User::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $user_id;

    #[ORM\OneToOne(targetEntity: Answers::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $answers_id;

    #[ORM\OneToOne(targetEntity: QCM::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $Qcm_id;

    #[ORM\OneToOne(targetEntity: Question::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $question_id;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $Result;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getAnswersId(): ?Answers
    {
        return $this->answers_id;
    }

    public function setAnswersId(Answers $answers_id): self
    {
        $this->answers_id = $answers_id;

        return $this;
    }

    public function getQcmId(): ?Qcm
    {
        return $this->Qcm_id;
    }

    public function setQcmId(Qcm $Qcm_id): self
    {
        $this->Qcm_id = $Qcm_id;

        return $this;
    }

    public function getQuestionId(): ?Question
    {
        return $this->question_id;
    }

    public function setQuestionId(Question $question_id): self
    {
        $this->question_id = $question_id;

        return $this;
    }

    public function getResult(): ?int
    {
        return $this->Result;
    }

    public function setResult(?int $Result): self
    {
        $this->Result = $Result;

        return $this;
    }
}