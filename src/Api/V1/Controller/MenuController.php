<?php

declare(strict_types=1);

namespace App\Api\V1\Controller;

use App\Api\V1\Dto\MenuDto;
use App\Api\V1\Repository\MenuRepository;
use App\Api\V1\Service\MenuService;
use JMS\Serializer\SerializationContext;
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
#[Route('/menus', name: 'api_v1_menus_')]
class MenuController extends ApiController
{
    /**
     * MenuController constructor.
     *
     * @param ValidatorInterface $validator The validator
     * @param SerializerInterface $serializer The serializer
     * @param MenuRepository $menuRepository The menu repository
     * @param MenuService $menuService The menu service
     */
    public function __construct(
        protected ValidatorInterface $validator,
        protected SerializerInterface $serializer,
        protected MenuRepository $menuRepository,
        protected MenuService $menuService
    ) {
        parent::__construct($validator, $serializer);
    }

    /**
     * Create a new menu entity and return it as a JSON response.
     *
     * @param Request $request The HTTP request object
     *
     * @return JsonResponse The JSON response containing the newly created menu entity
     */
    #[Route(null, name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        /** @var MenuDto $menuDto */
        $menuDto = $this->serializer->deserialize($request->getContent(), MenuDto::class, 'json');

        $errors = $this->validateDto($menuDto);
        if ($errors) {
            return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
        }

        $menu = $this->menuService->create($menuDto);

        $context = SerializationContext::create()->setGroups(['menu']);
        $response = $this->serializer->serialize($menu, 'json', $context);

        return new JsonResponse(json_decode($response), JsonResponse::HTTP_CREATED);
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
            return $this->entityNotFoundResponse('Menu', $menuId);
        }

        $this->menuService->delete($menu);

        return new JsonResponse(
            ['message' => sprintf('Menu item "%s" deleted', $menuId)],
            JsonResponse::HTTP_OK
        );
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
        $response = $this->serializer->serialize($menus, 'json');

        return new JsonResponse(json_decode($response), JsonResponse::HTTP_OK);
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
            return $this->entityNotFoundResponse('Menu', $menuId);
        }

        $response = $this->serializer->serialize($menu, 'json');

        return new JsonResponse(json_decode($response), JsonResponse::HTTP_OK);
    }

    /**
     * Updates a menu entity and return it as a JSON response.
     *
     * @param Request $request The HTTP request object
     * @param int $menuId The id of the menu item
     *
     * @return JsonResponse The JSON response containing the newly updated menu entity
     */
    #[Route('/{menuId}', name: 'update', methods: ['PATCH', 'PUT'])]
    public function update(Request $request, int $menuId): JsonResponse
    {
        $menu = $this->menuRepository->find($menuId);
        if (! $menu) {
            return $this->entityNotFoundResponse('Menu', $menuId);
        }

        $menuData = $this->mergeEntityAndPayload($menu, $request);
        /** @var MenuDto $menuDto */
        $menuDto = $this->serializer->deserialize($menuData, MenuDto::class, 'json');

        $errors = $this->validateDto($menuDto);
        if ($errors) {
            return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
        }

        $menu = $this->menuService->update($menu, $menuDto);
        $context = $this->getContext(true, ['menu']);
        $serializedMenu = $this->serializer->serialize($menu, 'json', $context);

        return new JsonResponse(json_decode($serializedMenu), JsonResponse::HTTP_OK);
    }
}
