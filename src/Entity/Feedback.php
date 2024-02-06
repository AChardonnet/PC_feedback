<?php

namespace App\Entity;

use App\Repository\FeedbackRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FeedbackRepository::class)]
class Feedback
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'feedback', targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\ManyToOne(inversedBy: 'feedback', targetEntity: Course::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Course $course = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $comment = null;

    #[Assert\Range(
        notInRangeMessage: "Les notes doivent être comprises entre {{ min }} et {{ max }}",
        min: 0,
        max: 5
    )]
    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $overall = null;

    #[Assert\Range(
        notInRangeMessage: "Les notes doivent être comprises entre {{ min }} et {{ max }}",
        min: 0,
        max: 5
    )]
    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $difficulty = null;

    #[Assert\Range(
        notInRangeMessage: "Les notes doivent être comprises entre {{ min }} et {{ max }}",
        min: 0,
        max: 5
    )]
    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $interest = null;

    #[ORM\Column]
    private ?bool $valid = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): static
    {
        $this->course = $course;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getOverall(): ?int
    {
        return $this->overall;
    }

    public function setOverall(int $overall): static
    {
        $this->overall = $overall;

        return $this;
    }

    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function setDifficulty(int $difficulty): static
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getInterest(): ?int
    {
        return $this->interest;
    }

    public function setInterest(int $interest): static
    {
        $this->interest = $interest;

        return $this;
    }

    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(bool $valid): static
    {
        $this->valid = $valid;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }
}
