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
 * Handles all menu related requests coming from the API.
 * 
 * @package App\Api\V1\Controller
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
#[Route('/api/v1/menus', name: 'api_v1_menus')]
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
     * Create a new menu entity and return it as a JSON response.
     *
     * @param Request $request The HTTP request object
     * 
     * @return JsonResponse The JSON response containing the newly created menu entity
     */
    #[Route('/', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $menuDto = $this->serializer->deserialize($request->getContent(), MenuDto::class, 'json');
        $errors = $this->validateDto($menuDto);
        if ($errors) {
            return $this->json(['errors' => $errors], 400);
        }

        $menu = $this->menuService->create($menuDto);
        $response = $this->serializer->serialize($menu, MenuSerializer::class);

        return $this->json($response, JsonResponse::HTTP_CREATED);
    }

    /**
     * Deletes a menu entity and return a JSON response.
     *
     * @param int $menuId The id of the menu item
     * 
     * @return JsonResponse The JSON response indicating the status of the deletion
     */
    #[Route('/{menuId}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $menuId): JsonResponse
    {
        $menu = $this->menuRepository->find($menuId);
        if (! $menu) {
            throw $this->createNotFoundException(sprintf('Menu item "%d" not found', $menuId));
        }

        $this->menuService->delete($menu);

        return $this->json(['message' => 'Menu item deleted'], JsonResponse::HTTP_OK);
    }
    
    /**
     * Get a JSON response of all menus.
     *
     * @return JsonResponse The JSON response
     */
    #[Route('/', name: 'index', methods:['GET'])]
    public function index(): JsonResponse
    {
        $menus = $this->menuRepository->findAll();
        $response = $this->serializer->serialize($menus, MenuSerializer::class);

        return $this->json($response, 200);
    }

    /**
     * Get a JSON response for a single menu item.
     *
     * @param int $menuId The ID of the menu item
     * 
     * @return JsonResponse The JSON response
     */
    #[Route('/{menuId}', name: 'show', methods:['GET'])]
    public function show(int $menuId): JsonResponse
    {
        $menu = $this->menuRepository->find($menuId);
        if (! $menu) {
            throw $this->createNotFoundException(sprintf('Menu item "%d" not found', $menuId));
        }
        
        $response = $this->serializer->serialize($menu, MenuSerializer::class);

        return $this->json($response, JsonResponse::HTTP_OK);
    }

    /**
     * Updates a menu entity and return it as a JSON response.
     *
     * @param Request $request The HTTP request object
     * @param int $menuId The id of the menu item
     * 
     * @return JsonResponse The JSON response containing the newly updated menu entity
     */
    #[Route('/{menuId}', name: 'update', methods: ['PUT'])]
    public function update(Request $request, int $menuId): JsonResponse
    {
        $menu = $this->menuRepository->find($menuId);
        if (! $menu) {
            throw $this->createNotFoundException(sprintf('Menu item "%d" not found', $menuId));
        }

        $menuDto = $this->serializer->deserialize($request->getContent(), MenuDto::class, 'json');
        $errors = $this->validateDto($menuDto);
        if ($errors) {
            return $this->json(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
        }

        $menu = $this->menuService->update($menu, $menuDto);

        return $this->json($menu, JsonResponse::HTTP_OK);
    }
}
