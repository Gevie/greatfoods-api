<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use App\Traits\Entity\LifecycleTrait;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 * 
 * Represents a user item in the application.
 * 
 * @package App\Entity
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks()]
#[ORM\Table(name: 'users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use LifecycleTrait;

    /**
     * The email attribute.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Serializer\Groups(['user'])]
    private ?string $email = null;

    /**
     * The id attribute.
     *
     * @var integer|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Serializer\Groups(['user'])]
    private ?int $id = null;

    /**
     * The password (hashed) attribute
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string')]
    #[Serializer\Groups(['user'])]
    private ?string $password = null;

    /**
     * The roles attribute.
     *
     * @var string[]
     */
    #[ORM\Column(type: 'json')]
    #[Serializer\Groups(['user'])]
    private array $roles = [];

    /**
     * Removes sensitive data from the user.
     * 
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     *
     * @return void
     */
    public function eraseCredentials(): void
    {
        // ...
    }

    /**
     * Gets the email of the user.
     *
     * @return string|null The email or null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Gets the id of the user.
     *
     * @return integer|null The id or null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets the password (hashed) of the user.
     *
     * @return string The password or null
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Gets the roles of the user.
     * 
     * Always includes a 'ROLE_USER' entry.
     *
     * @return string[] The roles
     */
    public function getRoles(): array
    {
        return array_unique($this->roles + ['ROLE_USER']);
    }

    /**
     * Gets the identifier of the user (email).
     *
     * @return string The identifier
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * Sets the email of the user.
     *
     * @param string $email The email address
     * 
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Sets the password of the user.
     *
     * @param string $password The password
     * 
     * @return self
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Sets the roles of the user.
     *
     * @param array $roles The roles
     * 
     * @return self
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
}
