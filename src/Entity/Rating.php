<?php

namespace App\Entity;

use App\Repository\RatingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RatingRepository::class)]
#[ORM\Index(name: 'idx_rating_seller', fields: ['seller'])]
#[ORM\UniqueConstraint(fields: ['seller', 'rater'])]
class Rating
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ratingsReceived')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $seller = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $rater = null;

    #[ORM\Column]
    #[Assert\Range(min: 1, max: 5)]
    private int $score = 5;

    #[ORM\Column(length: 500, nullable: true)]
    #[Assert\Length(max: 500)]
    private ?string $comment = null;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getSeller(): ?User { return $this->seller; }
    public function setSeller(?User $seller): static { $this->seller = $seller; return $this; }
    public function getRater(): ?User { return $this->rater; }
    public function setRater(?User $rater): static { $this->rater = $rater; return $this; }
    public function getScore(): int { return $this->score; }
    public function setScore(int $score): static { $this->score = $score; return $this; }
    public function getComment(): ?string { return $this->comment; }
    public function setComment(?string $comment): static { $this->comment = $comment; return $this; }
    public function getCreatedAt(): \DateTimeImmutable { return $this->createdAt; }
}
