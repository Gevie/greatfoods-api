<?php

declare(strict_types=1);

namespace App\Traits\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Trait LifecycleTrait
 * 
 * Adds lifecycle callback properties, getters and setters for entities using
 * created, modified and deleted (soft delete).
 * 
 * @package App\Traits\Entity
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
trait LifecycleTrait
{
    /**
     * The created attribute.
     *
     * @var DateTimeInterface
     */
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    #[Serializer\Groups(['lifecycle'])]
    private ?DateTimeInterface $created = null;

    /**
     * The deleted attribute.
     *
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Serializer\Groups(['deleted'])]
    private ?DateTimeInterface $deleted = null;

    /**
     * The modified attribute.
     *
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    #[Serializer\Groups(['lifecycle'])]
    private ?DateTimeInterface $modified = null;

    /**
     * Soft deletes the entity.
     *
     * @return void
     */
    public function delete(): void
    {
        $this->deleted = new DateTimeImmutable();
    }

    /**
     * Gets the created timestamp of the entity.
     *
     * @return DateTimeInterface|null The created timestamp or null
     */
    public function getCreated(): ?DateTimeInterface
    {
        return $this->created;
    }

    /**
     * Gets the deleted timestamp of the entity.
     *
     * @return DateTimeInterface|null The deleted timestamp or null
     */
    public function getDeleted(): ?DateTimeInterface
    {
        return $this->deleted;
    }

    /**
     * Gets the modified timestamp of the entity.
     *
     * @return DateTimeInterface|null The modified timestamp or null
     */
    public function getModified(): ?DateTimeInterface
    {
        return $this->modified;
    }

    /**
     * Determines whether the entity has been deleted.
     *
     * @return boolean True if deleted, false otherwise
     */
    public function isDeleted(): bool
    {
        return $this->deleted !== null;
    }

    /**
     * Sets the created timestamp of the entity to the current time on persist.
     *
     * @return void
     */
    #[ORM\PrePersist]
    public function setCreatedOnPersist(): void
    {
        $this->created = new DateTimeImmutable();
    }

    /**
     * Sets the modified timestamp of the entity to the current time on persist.
     *
     * @return void
     */
    #[ORM\PreUpdate]
    public function setModifiedOnUpdate(): void
    {
        $this->modified = new DateTimeImmutable();
    }
}
