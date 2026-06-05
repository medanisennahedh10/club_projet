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

    public function __construct()
    {
        $this->clubs = new ArrayCollection();
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
}

