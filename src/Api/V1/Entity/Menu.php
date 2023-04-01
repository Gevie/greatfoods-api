<?php

declare(strict_types=1);

namespace App\Api\V1\Entity;

use App\Api\V1\Repository\MenuRepository;
use App\Entity\Menu as AbstractMenu;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
class Menu extends AbstractMenu
{
    #[ORM\Column(length: 128)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(name: 'order', type: Types::SMALLINT, nullable: true, options: ['unsigned' => true])]
    private ?int $order = null;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
    public function getOrder(): ?int
    {
        return $this->order;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setOrder(?int $order): self
    {
        $this->order = $order;

        return $this;
    }
}
