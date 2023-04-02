<?php

declare(strict_types=1);

namespace App\Api\V1\Dto;

use App\Contracts\Dto\Dto as DtoInterface;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class MenuDto
 * 
 * A Data Transfer Object (DTO) used to move menu data around the system.
 * 
 * @package App\Api\V1\Dto
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
class MenuDto implements DtoInterface
{
    /**
     * The name of the menu.
     * 
     * @var string
     */
    #[Serializer\Type('string')]
    #[Assert\NotBlank(message: 'Name cannot be blank')]
    #[Assert\Length(max: 128, maxMessage: 'Name cannot be longer than {{ limit }} characters')]
    #[Serializer\Since('1.0')]
    public string $name = '';

    /**
     * The description of the menu.
     * 
     * @var string|null
     */
    #[Serializer\Type('string')]
    #[Assert\Length(max: 255, maxMessage: 'Description cannot be longer than {{ limit }} characters')]
    #[Serializer\Since('1.0')]
    public ?string $description = null;

    /**
     * The order of the menu.
     *
     * @var integer|null
     */
    #[Serializer\Type('integer')]
    #[Assert\PositiveOrZero(message: 'Order must be a positive integer or zero')]
    #[Assert\Unique(message: 'Order must be unique and cannot be shared with another menu entry')]
    #[Serializer\Since('1.0')]
    public ?int $order = null;

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