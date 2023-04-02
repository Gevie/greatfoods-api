<?php

declare(strict_types=1);

namespace App\Entity;

use App\Contracts\Entity\Menu as MenuInterface;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Menu
 *
 * Represents a menu item in the application.
 *
 * @package App\Entity
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
#[ORM\HasLifecycleCallbacks()]
#[ORM\Table(name: 'menus')]
abstract class Menu implements MenuInterface
{
    /**
     * The id attribute.
     *
     * @var integer|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * The created attribute.
     *
     * @var DateTimeInterface
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    private DateTimeInterface $created;

    /**
     * The modified attribute.
     *
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $modified = null;

    /**
     * The deleted attribute.
     *
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $deleted = null;

    /**
     * Soft deletes the menu item.
     *
     * @return void
     */
    public function delete(): void
    {
        $this->deleted = new DateTimeImmutable();
    }

    /**
     * Gets the created timestamp of the menu item.
     *
     * @return DateTimeInterface|null The created timestamp or null
     */
    public function getCreated(): ?DateTimeInterface
    {
        return $this->created;
    }

    /**
     * Gets the deleted timestamp of the menu item.
     *
     * @return DateTimeInterface|null The deleted timestamp or null
     */
    public function getDeleted(): ?DateTimeInterface
    {
        return $this->deleted;
    }

    /**
     * Gets the id of the menu item.
     *
     * @return integer|null The menu id or null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the modified timestamp of the menu item.
     *
     * @return DateTimeInterface|null The modified timestamp or null
     */
    public function getModified(): ?DateTimeInterface
    {
        return $this->modified;
    }

    /**
     * Determines whether the menu item has been deleted.
     *
     * @return boolean True if deleted, false otherwise
     */
    public function isDeleted(): bool
    {
        return $this->deleted !== null;
    }

    /**
     * Restores a deleted menu item.
     *
     * @return void
     */
    public function restore(): void
    {
        $this->deleted = null;
    }

    /**
     * Sets the created timestamp of the menu item to the current time on persist.
     *
     * @return void
     */
    #[ORM\PrePersist]
    public function setCreatedOnPersist(): void
    {
        $this->created = new DateTimeImmutable();
    }

    /**
     * Sets the modified timestamp of the menu item to the current time on persist.
     *
     * @return void
     */
    #[ORM\PreUpdate]
    public function setModifiedOnUpdate(): void
    {
        $this->modified = new DateTimeImmutable();
    }
}
