<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 *
 * @Serializer\ExclusionPolicy("all")
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
     * @Assert\Blank(
     *     groups = {"create"},
     *     message = "User id must not be defined"
     * )
     *
     * @Serializer\Expose()
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
     *
     * @Serializer\Expose()
     */
    private string $email;

    /**
     * @ORM\Column(type="json")
     *
     * @Assert\All(
     *     @Assert\Choice(
     *         groups = {"create"},
     *         choices = User::ROLES,
     *         message = "You must provid a valid user role. Roles available: {{ choices }}"
     *     )
     * )
     *
     * @Serializer\Expose()
     */
    private array $roles = [];

    /**
     * @ORM\Column(type="string")
     */
    private string $password;

    /**
     * @Assert\NotBlank(
     *     groups = {"create"},
     *     message = "The password cannot be blank"
     * )
     * @Assert\Regex(
     *     pattern = "/(?=^.{8,40}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/",
     *     message = "Your password must contain at least 8 characters and a maximum of 40 characters including one lower case, one upper case and one number"
     * )
     */
    private ?string $plainPassword = null;

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
     * @Serializer\Expose()
     */
    private string $fullname;

    public function __construct()
    {
        $this->createdAt = new DateTime();
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

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

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
