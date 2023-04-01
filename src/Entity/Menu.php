<?php

declare(strict_types=1);

namespace App\Entity;

use App\Contracts\Entity\Menu as MenuInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks()]
#[ORM\Table(name: 'menus')]
abstract class Menu implements MenuInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModified(): ?\DateTimeInterface
    {
        return $this->modified;
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

    #[ORM\PreUpdate]
    public function setModifiedOnUpdate(): void
    {
        $this->modified = new \DateTimeImmutable;
    }
}