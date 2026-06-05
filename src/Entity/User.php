<?php

namespace App\Entity;

use App\Enum\UserRole;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(options: ['default' => false])]
    private bool $is_verified = false;

    #[ORM\Column(type: 'string', enumType: UserRole::class)]
    private UserRole $role = UserRole::STUDENT;

    #[ORM\Column(length: 255)]
    private ?string $dtype = null; // Khallitha kima hiya ken ma l9itech Inheritance, ama ken famma inheritance a3melli sign.

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profile_picture = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $otp_code = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $otp_expires_at = null;

    /**
     * @var Collection<int, Club>
     */
    #[ORM\OneToMany(targetEntity: Club::class, mappedBy: 'proposed_by_id')]
    private Collection $clubs;

    /**
     * @var Collection<int, Reclamation>
     */
    #[ORM\OneToMany(targetEntity: Reclamation::class, mappedBy: 'user_id')]
    private Collection $reclamations;

    /**
     * @var Collection<int, ClubMember>
     */
    #[ORM\OneToMany(targetEntity: ClubMember::class, mappedBy: 'user_id')]
    private Collection $clubMembers;

<<<<<<< Updated upstream
    /**
 * @var Collection<int, Candidature>
 */
#[ORM\OneToMany(mappedBy: 'user', targetEntity: Candidature::class, orphanRemoval: true)]
private Collection $candidatures;

=======
   /**
 * @var Collection<int, Candidature>
 */
#[ORM\OneToMany(mappedBy: 'user', targetEntity: Candidature::class)]
private Collection $candidatures;
>>>>>>> Stashed changes
    /**
     * @var Collection<int, Participation>
     */
    #[ORM\OneToMany(targetEntity: Participation::class, mappedBy: 'user_id', orphanRemoval: true)]
    private Collection $participations;

    /**
     * @var Collection<int, Feedback>
     */
    #[ORM\OneToMany(targetEntity: Feedback::class, mappedBy: 'user_id', orphanRemoval: true)]
    private Collection $feedback;

    public function __construct()
    {
        $this->clubs = new ArrayCollection();
        $this->reclamations = new ArrayCollection();
        $this->clubMembers = new ArrayCollection();
        $this->candidatures = new ArrayCollection();
        $this->participations = new ArrayCollection();
        $this->feedback = new ArrayCollection();
    }

    // --- UserInterface ---

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /** @return string[] */
    public function getRoles(): array
    {
        // Symfony dima y7eb format ROLE_XXX
        $roles = [$this->role->value];
        
        // Garanti dima el user 3andou 3al a9al ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function eraseCredentials(): void {}

    // --- Getters / Setters ---

    public function getId(): ?int { return $this->id; }

    public function getFirstname(): ?string { return $this->firstname; }
    public function setFirstname(string $firstname): static
    { $this->firstname = $firstname; return $this; }

    public function getLastname(): ?string { return $this->lastname; }
    public function setLastname(string $lastname): static
    { $this->lastname = $lastname; return $this; }

    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): static
    { $this->email = $email; return $this; }

    public function getPassword(): ?string { return $this->password; }
    public function setPassword(string $password): static
    { $this->password = $password; return $this; }

    public function isVerified(): bool { return $this->is_verified; }
    public function setIsVerified(bool $is_verified): static
    { $this->is_verified = $is_verified; return $this; }

    public function getRole(): UserRole { return $this->role; }
    public function setRole(UserRole $role): static
    { $this->role = $role; return $this; }

    public function getDtype(): ?string { return $this->dtype; }
    public function setDtype(string $dtype): static
    { $this->dtype = $dtype; return $this; }

    public function getProfilePicture(): ?string { return $this->profile_picture; }
    public function setProfilePicture(?string $profile_picture): static
    { $this->profile_picture = $profile_picture; return $this; }

    public function getOtpCode(): ?string { return $this->otp_code; }
    public function setOtpCode(?string $otp_code): static
    { $this->otp_code = $otp_code; return $this; }

    // Hna tsala7 el type l `\DateTimeImmutable` kima l property elfoug
    public function getOtpExpiresAt(): ?\DateTimeImmutable { return $this->otp_expires_at; }
    public function setOtpExpiresAt(?\DateTimeImmutable $otp_expires_at): static
    { $this->otp_expires_at = $otp_expires_at; return $this; }

    /**
     * @return Collection<int, Club>
     */
    public function getClubs(): Collection
    {
        return $this->clubs;
    }

    public function addClub(Club $club): static
    {
        if (!$this->clubs->contains($club)) {
            $this->clubs->add($club);
            $club->setProposedById($this);
        }

        return $this;
    }

    public function removeClub(Club $club): static
    {
        if ($this->clubs->removeElement($club)) {
            // set the owning side to null (unless already changed)
            if ($club->getProposedById() === $this) {
                $club->setProposedById(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reclamation>
     */
    public function getReclamations(): Collection
    {
        return $this->reclamations;
    }

    public function addReclamation(Reclamation $reclamation): static
    {
        if (!$this->reclamations->contains($reclamation)) {
            $this->reclamations->add($reclamation);
            $reclamation->setUserId($this);
        }

        return $this;
    }

    public function removeReclamation(Reclamation $reclamation): static
    {
        if ($this->reclamations->removeElement($reclamation)) {
            // set the owning side to null (unless already changed)
            if ($reclamation->getUserId() === $this) {
                $reclamation->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ClubMember>
     */
    public function getClubMembers(): Collection
    {
        return $this->clubMembers;
    }

    public function addClubMember(ClubMember $clubMember): static
    {
        if (!$this->clubMembers->contains($clubMember)) {
            $this->clubMembers->add($clubMember);
            $clubMember->setUserId($this);
        }

        return $this;
    }

    public function removeClubMember(ClubMember $clubMember): static
    {
        if ($this->clubMembers->removeElement($clubMember)) {
            // set the owning side to null (unless already changed)
            if ($clubMember->getUserId() === $this) {
                $clubMember->setUserId(null);
            }
        }

        return $this;
    }

<<<<<<< Updated upstream
/**
=======
    /**
>>>>>>> Stashed changes
 * @return Collection<int, Candidature>
 */
public function getCandidatures(): Collection
{
    return $this->candidatures;
}

public function addCandidature(Candidature $candidature): static
{
    if (!$this->candidatures->contains($candidature)) {
        $this->candidatures->add($candidature);
        $candidature->setUser($this);
    }

    return $this;
}

public function removeCandidature(Candidature $candidature): static
{
    if ($this->candidatures->removeElement($candidature)) {
        if ($candidature->getUser() === $this) {
            $candidature->setUser(null);
        }
    }
<<<<<<< Updated upstream

    return $this;
}
=======
>>>>>>> Stashed changes

    return $this;
}
    /**
     * @return Collection<int, Participation>
     */
    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    public function addParticipation(Participation $participation): static
    {
        if (!$this->participations->contains($participation)) {
            $this->participations->add($participation);
            $participation->setUserId($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): static
    {
        if ($this->participations->removeElement($participation)) {
            // set the owning side to null (unless already changed)
            if ($participation->getUserId() === $this) {
                $participation->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Feedback>
     */
    public function getFeedback(): Collection
    {
        return $this->feedback;
    }

    public function addFeedback(Feedback $feedback): static
    {
        if (!$this->feedback->contains($feedback)) {
            $this->feedback->add($feedback);
            $feedback->setUserId($this);
        }

        return $this;
    }

    public function removeFeedback(Feedback $feedback): static
    {
        if ($this->feedback->removeElement($feedback)) {
            // set the owning side to null (unless already changed)
            if ($feedback->getUserId() === $this) {
                $feedback->setUserId(null);
            }
        }

        return $this;
    }
}

