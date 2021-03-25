<?php


namespace App\Entity\Traits;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

trait TimestampableTrait
{
    /**
     * @ORM\Column(type="datetime")
     *
     * @Serializer\Groups({"read"})
     */
    private DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Serializer\Groups({"read"})
     */
    private ?DateTimeInterface $updatedAt = null;

    /**
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(DateTimeInterface $createdAt):  self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeInterface|null $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt(?DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
