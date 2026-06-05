<?php

namespace App\Entity;

use App\Repository\CandidatureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CandidatureRepository::class)]
class Candidature
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'candidatures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'candidatures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Recrutement $recrutement_id = null;

    #[ORM\Column(length: 255)]
    private ?string $cv_filename = null;

    #[ORM\Column(length: 255)]
    private ?string $message = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $submitted_at = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getRecrutementId(): ?Recrutement
    {
        return $this->recrutement_id;
    }

    public function setRecrutementId(?Recrutement $recrutement_id): static
    {
        $this->recrutement_id = $recrutement_id;

        return $this;
    }

    public function getCvFilename(): ?string
    {
        return $this->cv_filename;
    }

    public function setCvFilename(string $cv_filename): static
    {
        $this->cv_filename = $cv_filename;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getSubmittedAt(): ?\DateTimeImmutable
    {
        return $this->submitted_at;
    }

    public function setSubmittedAt(\DateTimeImmutable $submitted_at): static
    {
        $this->submitted_at = $submitted_at;

        return $this;
    }
}