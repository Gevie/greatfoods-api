<?php

declare(strict_types=1);

namespace App\Api\V1\Controller;

use App\Api\V1\Dto\UserDto;
use App\Api\V1\Service\UserService;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class RegistrationController.
 * 
 * Handles all registration related requests coming from the API.
 * 
 * @package App\Api\V1\Controller
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
#[Route('/registration', name: 'api_v1_registration_')]
class RegistrationController extends ApiController
{
    /**
     * RegistrationController constructor.
     *
     * @param ValidatorInterface $validator The validator
     * @param SerializerInterface $serializer The serializer
     * @param UserService $userService The user service
     */
    public function __construct(
        protected ValidatorInterface $validator,
        protected SerializerInterface $serializer,
        protected UserService $userService
    ) {
        // ...
    }
    
    /**
     * Register a new user entity and return it as a JSON response.
     *
     * @param Request $request The HTTP request object
     * 
     * @return JsonResponse The JSON response containing the newly created user entity or error list
     */
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        /** @var UserDto */
        $userDto = $this->serializer->deserialize($request->getContent(), UserDto::class, 'json');
        
        $errors = $this->validateDto($userDto);
        if ($errors) {
            return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
        }

        $user = $this->userService->create($userDto);

        $context = SerializationContext::create()->setGroups(['user']);
        $response = $this->serializer->serialize($user, 'json', $context);

        return new JsonResponse(json_decode($response), JsonResponse::HTTP_CREATED);
    }

    // TODO: Email Verification
}
