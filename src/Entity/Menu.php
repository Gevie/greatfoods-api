<?php

declare(strict_types=1);

namespace App\Entity;

use App\Api\V1\Repository\MenuRepository;
use App\Contracts\Entity\Lifecycle as LifecycleInterface;
use App\Entity\AbstractEntity;
use App\Traits\Entity\LifecycleTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class Menu
 *
 * Represents a menu item in the application.
 *
 * @package App\Entity
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
#[ORM\Entity(repositoryClass: MenuRepository::class)]
#[ORM\HasLifecycleCallbacks()]
#[ORM\Table(name: 'menus')]
class Menu extends AbstractEntity implements LifecycleInterface
{
    use LifecycleTrait;

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
    #[Serializer\Groups(['menu'])]
    protected ?int $id = null;

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
    #[Serializer\SerializedName('order')]
    private ?int $order = null;

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
