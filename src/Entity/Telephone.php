<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Repository\TelephoneRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TelephoneRepository::class)
 *
 * @Hateoas\Relation(
 *     "self",
 *     href = @Hateoas\Route(
 *         "app_telephone_show",
 *         parameters = { "id" = "expr(object.getId())" },
 *         absolute = true
 *     ),
 *     exclusion = @Hateoas\Exclusion(
 *         groups = { "read" }
 *     )
 * )
 */
class Telephone
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @Serializer\Groups({"read"})
     * @OA\Property(description="The unique identifier of the telephone")
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
     *
     * @Serializer\Groups({"read"})
     * @OA\Property(description="The phone name")
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=30)
     *
     * @Assert\NotBlank(
     *     message = "The reference cannot be blank"
     * )
     * @Assert\Length(
     *     max = 30,
     *     maxMessage = "The reference cannot exceed {{ limit }} characters"
     * )
     *
     * @Serializer\Groups({"read"})
     * @OA\Property(description="The phone reference")
     */
    private string $reference;

    /**
     * @ORM\Column(type="string", length=80)
     *
     * @Assert\NotBlank(
     *     message = "The brand name cannot be blank"
     * )
     * @Assert\Length(
     *     max = 80,
     *     maxMessage = "The brand name cannot exceed {{ limit }} characters"
     * )
     *
     * @Serializer\Groups({"read"})
     * @OA\Property(description="The brand name of the phone")
     */
    private string $brand;

    /**
     * @ORM\Column(type="float", nullable=true)
     *
     * @Assert\Type(
     *     type = {"float", "int"},
     *     message = "The price should be a valid number"
     * )
     * @Assert\Positive(
     *     message = "The price should be positive and greater than 0"
     * )
     *
     * @Serializer\Groups({"read"})
     * @OA\Property(
     *     type = "number",
     *     format = "float",
     *     description = "The price of the phone in euro"
     * )
     */
    private ?float $price;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Assert\Length(
     *     max = 1000,
     *     maxMessage = "The description cannot exceed {{ limit }} characters"
     * )
     *
     * @Serializer\Groups({"read"})
     * @OA\Property(description="The description of the phone ")
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
