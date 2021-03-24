<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\TelephoneRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass=TelephoneRepository::class)
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Telephone
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Serializer\Expose()
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=80)
     *
     * @Serializer\Expose()
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=30)
     *
     * @Serializer\Expose()
     */
    private string $reference;

    /**
     * @ORM\Column(type="string", length=80)
     *
     * @Serializer\Expose()
     */
    private string $brand;

    /**
     * @ORM\Column(type="float", nullable=true)
     *
     * @Serializer\Expose()
     */
    private ?float $price;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Serializer\Expose()
     */
    private ?string $description;

    public function __construct()
    {
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

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
