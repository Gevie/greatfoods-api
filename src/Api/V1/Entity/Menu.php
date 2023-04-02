<?php

declare(strict_types=1);

namespace App\Api\V1\Entity;

use App\Api\V1\Repository\MenuRepository;
use App\Entity\Menu as AbstractMenu;
use DateTimeInterface;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class Menu
 *
 * Represents a menu item in the application.
 *
 * @package App\Api\V1\Entity
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
#[ORM\Entity(repositoryClass: MenuRepository::class)]
#[ORM\HasLifecycleCallbacks()]
#[ORM\Table(name: 'menus')]
class Menu extends AbstractMenu
{
    /**
     * The created attribute.
     *
     * @var DateTimeInterface
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?DateTimeInterface $created = null;

    /**
     * The deleted attribute.
     *
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $deleted = null;

    /**
     * The description attribute.
     *
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    #[Serializer\Groups(['menu'])]
    private ?string $description = null;

    /**
     * The id attribute.
     *
     * @var integer|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    /**
     * The modified attribute.
     *
     * @var DateTimeInterface|null
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $modified = null;

    /**
     * The name attribute.
     *
     * @var string|null
     */
    #[ORM\Column(length: 128)]
    #[Serializer\Groups(['menu'])]
    private ?string $name = null;

    /**
     * The order attribute.
     *
     * @var integer|null
     */
    #[ORM\Column(name: '`order`', type: Types::SMALLINT, nullable: true, options: ['unsigned' => true])]
    #[Serializer\Groups(['menu'])]
    private ?int $order = null;

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
     * Gets the description of the menu item.
     *
     * @return string|null The description or null
     */
    public function getDescription(): ?string
    {
        return $this->description;
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
     * Gets the name of the menu item.
     *
     * @return string|null The name or null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Gets the order index of the menu item.
     *
     * @return integer|null The order index or null
     */
    public function getOrder(): ?int
    {
        return $this->order;
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
     * Sets the description of the menu item.
     *
     * @param string|null $description The description or null
     *
     * @return self
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
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

    /**
     * Sets the name of the menu item.
     *
     * @param string $name The name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Sets the order of the menu item.
     *
     * @param integer|null $order The order or null
     *
     * @return self
     */
    public function setOrder(?int $order): self
    {
        $this->order = $order;

        return $this;
    }
}
