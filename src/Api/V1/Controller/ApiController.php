<?php

declare(strict_types=1);

namespace App\Api\V1\Controller;

use App\Contracts\Dto\Dto as DtoInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ApiController
 * 
 * The abstract api controller with common functionality for all children.
 * 
 * @package App\Api\V1\Controller
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
abstract class ApiController extends AbstractController
{
    /**
     * ApiController constructor.
     *
     * @param ValidatorInterface $validator The validator
     */
    public function __construct(protected ValidatorInterface $validator)
    {
        // ..
    }

    /**
     * Validates a passed Data Transfer Object.
     *
     * @param DtoInterface $dto The data transfer object to validate
     * @param Constraint|Constraint[]|null $constraints Any optional constraints
     * 
     * @return string[]|\Stringable[] An error of validation errors, an empty array means no errors
     */
    protected function validateDto(DtoInterface $dto, Constraint|array $constraints = null): array
    {
        $violations = $this->validator->validate($dto, $constraints, $dto->getValidationGroups());

        $errors = [];
        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $errors;
    }
}