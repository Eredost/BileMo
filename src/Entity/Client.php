<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\ClientRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 */
class Client
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=80)
     *
     * @Assert\NotBlank(
     *     message = "The name cannot be blank"
     * )
     * @Assert\Length(
     *     max = 80,
     *     maxMessage = "The name cannot exceed {{ limit }} characters"
     * )
     */
    private string $name;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="client", orphanRemoval=true)
     */
    private Collection $users;

    public function __construct()
    {
        $this->users     = new ArrayCollection();
        $this->createdAt = new DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setClient($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        // set the owning side to null (unless already changed)
        if ($this->users->removeElement($user)
            && $user->getClient() === $this) {
            $user->setClient(null);
        }

        return $this;
    }
}
