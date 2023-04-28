<?php

declare(strict_types=1);

namespace App\Api\V1\Dto;

use App\Contracts\Dto\Dto as DtoInterface;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class UserDto implements DtoInterface
{
    /**
     * The unique identifier of the user.
     *
     * @var integer|null
     */
    #[Serializer\Type('integer')]
    #[Assert\Positive(message: 'ID must be a positive integer')]
    #[Serializer\Since('1.0')]
    public ?int $id = null;

    /**
     * The date and time when the user was created.
     *
     * @var \DateTime|null
     */
    #[Serializer\Type('DateTimeImmutable<"Y-m-d H:i:s">')]
    #[Serializer\Since('1.0')]
    public ?\DateTimeInterface $created = null;

    /**
     * The date and time when the user was last modified.
     *
     * @var \DateTime|null
     */
    #[Serializer\Type('DateTimeImmutable<"Y-m-d H:i:s">')]
    #[Serializer\Since('1.0')]
    public ?\DateTimeInterface $modified = null;

    /**
     * The date and time when the user was deleted.
     *
     * @var \DateTime|null
     */
    #[Serializer\Type('DateTimeImmutable<"Y-m-d H:i:s">')]
    #[Serializer\Since('1.0')]
    public ?\DateTimeInterface $deleted = null;
    
    /**
     * The email of the user.
     *
     * @var string
     */
    #[Serializer\Type('string')]
    #[Assert\NotBlank(message: 'Email cannot be blank')]
    #[Assert\Length(max: 180, maxMessage: 'Email cannot be longer than {{ limit }} characters')]
    #[Serializer\Since('1.0')]
    public string $email = '';

    /**
     * The password of the user.
     *
     * @var string
     */
    #[Serializer\Type('string')]
    #[Assert\NotBlank(message: 'Password cannot be blank')]
    #[Serializer\Since('1.0')]
    public string $password = '';

    /**
     * The roles for the user.
     *
     * @var string
     */
    #[Serializer\Type('json')]
    #[Assert\NotBlank(message: 'Roles cannot be blank')]
    #[Serializer\Since('1.0')]
    public array $roles = [];

    /**
     * Returns an array of validation groups for a given operation.
     *
     * @return array<string> The validation groups
     */
    public function getValidationGroups(): array
    {
        return ['create'];
    }
}
