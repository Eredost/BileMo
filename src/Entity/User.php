<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\UserRepository;
use App\Validator\UniqueEmail;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 */
class User implements UserInterface
{
    public const ROLES = [
        'ROLE_USER',
        'ROLE_ADMIN'
    ];

    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Serializer\Groups({"read"})
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     *
     * @Assert\NotBlank(
     *     groups = {"create"},
     *     message = "The email cannot be blank"
     * )
     * @Assert\Email(
     *     groups = {"create"},
     *     message = "The email '{{ value }}' is not a valid email address"
     * )
     * @UniqueEmail(
     *     groups = {"create"}
     * )
     *
     * @Serializer\Groups({"create", "read"})
     */
    private string $email;

    /**
     * @ORM\Column(type="json")
     *
     * @Assert\Type(
     *     type = "array",
     *     message = "The roles sould be a valid array"
     * )
     * @Assert\All(
     *     @Assert\Choice(
     *         groups = {"create"},
     *         choices = User::ROLES,
     *         message = "You must provid a valid user role. Roles available: {{ choices }}"
     *     )
     * )
     *
     * @Serializer\Groups({"create", "read"})
     * @Serializer\Type("array")
     */
    private array $roles = [];

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(
     *     groups = {"create"},
     *     message = "The password cannot be blank"
     * )
     * @Assert\Regex(
     *     groups = {"create"},
     *     pattern = "/(?=^.{8,40}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/",
     *     message = "Your password must contain at least 8 characters and a maximum of 40 characters including one lower case, one upper case and one number"
     * )
     *
     * @Serializer\Groups({"create"})
     */
    private string $password;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private Client $client;

    /**
     * @ORM\Column(type="string", length=80)
     *
     * @Assert\NotBlank(
     *     groups = {"create"},
     *     message = "The user fullname cannot be blank"
     * )
     * @Assert\Length(
     *     max = 80,
     *     maxMessage = "The fullname cannot exceed {{ limit }} characters"
     * )
     *
     * @Serializer\Groups({"create", "read"})
     */
    private string $fullname;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->plainPassword = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): self
    {
        $this->fullname = $fullname;

        return $this;
    }
}
