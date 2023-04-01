<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
#[ORM\HasLifecycleCallbacks()]
#[ORM\Table(name: 'menus')]
class Menu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 128)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(name: 'order', type: Types::SMALLINT, nullable: true, options: ['unsigned' => true])]
    private ?int $order = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: false)]
    private \DateTimeInterface $created;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $modified = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $deleted = null;

    public function delete(): void
    {
        $this->deleted = new \DateTimeImmutable;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function getDeleted(): ?\DateTimeInterface
    {
        return $this->deleted;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModified(): ?\DateTimeInterface
    {
        return $this->modified;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
    public function getOrder(): ?int
    {
        return $this->order;
    }

    public function isDeleted(): bool
    {
        return $this->deleted !== null;
    }

    public function restore(): void
    {
        $this->deleted = null;
    }

    #[ORM\PrePersist]
    public function setCreatedOnPersist(): void
    {
        $this->created = new \DateTimeImmutable;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    #[ORM\PreUpdate]
    public function setModifiedOnUpdate(): void
    {
        $this->modified = new \DateTimeImmutable;
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
