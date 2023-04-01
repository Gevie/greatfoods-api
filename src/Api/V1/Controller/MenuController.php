<?php

declare(strict_types=1);

namespace App\Api\V1\Controller;

use App\Api\V1\Dto\MenuDto;
use App\Api\V1\Repository\MenuRepository;
use App\Api\V1\Serializer\MenuSerializer;
use App\Api\V1\Service\MenuService;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class MenuController.
 * 
 * @package App\Api\V1\Controller
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
#[Route('/api', name: 'api_')]
class MenuController extends ApiController
{
    /**
     * MenuController constructor.
     *
     * @param SerializerInterface $serializer The serializer
     * @param MenuRepository $menuRepository The menu repository
     */
    public function __construct(
        protected ValidatorInterface $validator,
        protected SerializerInterface $serializer,
        protected MenuRepository $menuRepository,
        protected MenuService $menuService
    ) {
        parent::__construct($validator);
    }

    /**
     * Get a JSON response of all menus
     *
     * @return JsonResponse The JSON response
     */
    #[Route('/menus', name: 'menus', methods:['GET'])]
    public function index(): JsonResponse
    {
        $menus = $this->menuRepository->findAll();
        $response = $this->serializer->serialize($menus, MenuSerializer::class);

        return $this->json($response, 200);
    }

    /**
     * Create a new menu entity and return it as a JSON response.
     *
     * @param Request $request The HTTP request object
     * 
     * @return JsonResponse The JSON response containing the newly created menu entity
     */
    #[Route('/menus', name: 'menus_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $menuDto = $this->serializer->deserialize(
            $request->getContent(),
            MenuDto::class,
            'json'
        );

        $errors = $this->validateDto($menuDto);
        if ($errors) {
            return $this->json(['errors' => $errors], 400);
        }

        $menu = $this->menuService->create($menuDto);
        $response = $this->serializer->serialize($menu, MenuSerializer::class);

        return $this->json($response, 201);
    }
}
