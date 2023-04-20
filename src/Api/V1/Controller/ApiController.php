<?php

declare(strict_types=1);

namespace App\Api\V1\Controller;

use App\Contracts\Dto\Dto as DtoInterface;
use App\Entity\AbstractEntity;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @param SerializerInterface $serializer The serializer
     */
    public function __construct(
        protected ValidatorInterface $validator,
        protected SerializerInterface $serializer
    ) {
        // ..
    }

    /**
     * Generates a response for when an entity could not be found.
     *
     * @param string $entityName The entity name
     * @param integer $primaryKey The primary key used for search
     *
     * @return JsonResponse The 404 json response
     */
    protected function entityNotFoundResponse(string $entityName, int $primaryKey): JsonResponse
    {
        return new JsonResponse(
            ['error' => sprintf('The "%s" record with id "%d" could not be found.', $entityName, $primaryKey)],
            JsonResponse::HTTP_NOT_FOUND
        );
    }

    /**
     * Creates an instance of SerializationContext to be used with the JMS serializer.
     *
     * @param bool $serializeNull Allow null values to be serialized or not (default: true)
     * @param array<string> $groups The serialization groups
     *
     * @return SerializationContext The serialization context
     */
    protected function getContext(bool $serializeNull = true, array $groups = []): SerializationContext
    {
        return SerializationContext::create()
            ->setGroups($groups)
            ->setSerializeNull($serializeNull);
    }

    /**
     * Takes an entity and merges it with the request payload.
     *
     * This is used for patch updates to prevent a DTO removing non-specified properties.
     *
     * @param AbstractEntity $entity The entity being updated
     * @param Request $request The request payload
     *
     * @return string The merged entity and payload as JSON
     */
    protected function mergeEntityAndPayload(AbstractEntity $entity, Request $request): string
    {
        $serializedEntity = $this->serializer->serialize($entity, 'json');

        $data = array_merge(
            (array) json_decode($serializedEntity, true, 512, JSON_THROW_ON_ERROR),
            (array) json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR)
        );

        $encodedData = json_encode($data);
        if (!$encodedData) {
            throw new RuntimeException(sprintf(
                'Could not encode data whilst merging, error: "%s"',
                json_last_error_msg()
            ));
        }

        return $encodedData;
    }

    /**
     * Validates a passed Data Transfer Object.
     *
     * @param DtoInterface $dto The data transfer object to validate
     *
     * @return string[]|\Stringable[] An error of validation errors, an empty array means no errors
     */
    protected function validateDto(DtoInterface $dto): array
    {
        $violations = $this->validator->validate($dto);

        $errors = [];
        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $errors;
    }
}
