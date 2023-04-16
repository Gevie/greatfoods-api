<?php

declare(strict_types=1);

namespace App\Tests\Unit\Api\V1\Controller;

use App\Api\V1\Controller\MenuController;
use App\Api\V1\Dto\MenuDto;
use App\Api\V1\Entity\Menu;
use App\Api\V1\Repository\MenuRepository;
use App\Api\V1\Serializer\MenuSerializer;
use App\Api\V1\Service\MenuService;
use App\Tests\Stubs\Dto\MenuDtoStub;
use App\Tests\Stubs\Entity\MenuStub;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class MenuControllerTest
 * 
 * @package App\Tests\Unit\Api\V1\Controller
 * @author Stephen Speakman <hellospeakman@gmail.com>
 * 
 * @group api
 * @group api_v1
 * @group controller
 */
class MenuControllerTest extends KernelTestCase
{
    /**
     * The menu controller.
     *
     * @var MenuController|null
     */
    private MenuController|null $menuController;

    /**
     * The menu repository.
     *
     * @var MenuRepository|MockObject|null
     */
    private MenuRepository|MockObject|null $menuRepository;

    /**
     * The menu service.
     *
     * @var MenuService|MockObject|null
     */
    private MenuService|MockObject|null $menuService;

    /**
     * The serializer.
     *
     * @var SerializerInterface|MockObject|null
     */
    private SerializerInterface|MockObject|null $serializer;

    /**
     * The validator.
     *
     * @var ValidatorInterface|MockObject|null
     */
    private ValidatorInterface|MockObject|null $validator;

    /**
     * Executed before each test case is run.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->menuRepository = $this->createMock(MenuRepository::class);
        $this->menuService = $this->createMock(MenuService::class);
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);

        $this->menuController = new MenuController(
            $this->validator,
            $this->serializer,
            $this->menuRepository,
            $this->menuService
        );
    }

    /**
     * Executed after each test case is run.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        $this->menuController = null;
        $this->menuRepository = null;
        $this->menuService = null;
        $this->serializer = null;
        $this->validator = null;

        parent::tearDown();
    }

    /**
     * Tests the create action.
     * 
     * @dataProvider menuDataProvider
     * 
     * @param MenuDto $menuDto The menu data transfer object
     * @param Menu $menu The menu entity
     * @param string $menuJson The menu as json
     *
     * @return void
     */
    public function testCreateAction(MenuDto $menuDto, Menu $menu, string $menuJson): void
    {
        // Arrange
        $constraintViolationList = $this->createMock(ConstraintViolationListInterface::class);
        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('getContent')
            ->willReturn($menuJson);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with($menuJson, MenuDto::class, 'json')
            ->willReturn($menuDto);

        $this->validator->expects($this->once())
            ->method('validate')
            ->with($menuDto, null, ['create'])
            ->willReturn($constraintViolationList);

        $this->menuService->expects($this->once())
            ->method('create')
            ->with($menuDto)
            ->willReturn($menu);
        
        $this->serializer->expects($this->once())
            ->method('serialize')
            ->with($menu, MenuSerializer::class)
            ->willReturn($menuJson);

        // Act
        $response = $this->menuController->create($request);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
        $this->assertSame($menuJson, $response->getContent());
    }

    /**
     * Tests the create action with validation errors.
     * 
     * @dataProvider menuDataProvider
     * 
     * @param MenuDto $menuDto The menu data transfer object
     * @param Menu $menu The menu entity
     * @param string $menuJson The menu as json
     *
     * @return void
     */
    public function testCreateActionValidationFailed(MenuDto $menuDto, Menu $menu, string $menuJson): void
    {
        // Arrange
        $errors = [
            'errors' => [
                'name' => 'Name cannot be blank',
                'description' => 'Description cannot be longer than 255 characters'
            ]
        ];
       
        $violations = array_map(function ($key, $error) {
            return new ConstraintViolation($error, null, [], null, $key, null);
        }, array_keys($errors['errors']), $errors['errors']);
        
        $constraintViolationList = new ConstraintViolationList($violations);
        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('getContent')
            ->willReturn($menuJson);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with($menuJson, MenuDto::class, 'json')
            ->willReturn($menuDto);

        $this->validator->expects($this->once())
            ->method('validate')
            ->with($menuDto, null, ['create'])
            ->willReturn($constraintViolationList);

        // Act
        $response = $this->menuController->create($request);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertSame(json_encode($errors), $response->getContent());
    }

    /**
     * Tests the delete action.
     *
     * @return void
     */
    public function testDeleteAction(): void
    {
        // Arrange
        $menuId = 1;
        $menu = MenuStub::create();
        $responseMessage = ['message' => 'Menu item deleted'];

        $this->menuRepository->expects($this->once())
            ->method('find')
            ->with($menuId)
            ->willReturn($menu);

        $this->menuService->expects($this->once())
            ->method('delete')
            ->with($menu);

        // Act
        $response = $this->menuController->delete($menuId);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertSame(json_encode($responseMessage), $response->getContent());
    }

    /**
     * Tests the delete action when the menu is not found.
     *
     * @return void
     */
    public function testDeleteActionNotFound(): void
    {
        // Arrange
        $menuId = 1;
        $this->menuRepository->expects($this->once())
            ->method('find')
            ->with($menuId)
            ->willReturn(null);

        // Assert
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage(sprintf('Menu item "%d" not found', $menuId));

        // Act
        $this->menuController->delete($menuId);
    }

    /**
     * Test the index method.
     *
     * @dataProvider menusDataProvider
     * 
     * @param array $menuEntities The menu entities
     * @param string $menuJson The menu enties as json
     * 
     * @return void
     */
    public function testIndexAction(array $menuEntities, string $menuJson): void
    {
        // Arrange
        $this->menuRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($menuEntities);

        $this->serializer->expects($this->once())
            ->method('serialize')
            ->with($menuEntities, 'json')
            ->willReturn($menuJson);

        // Act
        $menus = $this->menuController->index();

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $menus);
        $this->assertEquals(JsonResponse::HTTP_OK, $menus->getStatusCode());
        $this->assertSame($menuJson, $menus->getContent());
    }

    /**
     * Tests the show action.
     *
     * @return void
     */
    public function testShowAction(): void
    {
        // Arrange
        $menuId = 1;
        $menuEntity = MenuStub::create();
        $menuJson = json_encode($menuEntity);

        $this->menuRepository->expects($this->once())
            ->method('find')
            ->with($menuId)
            ->willReturn($menuEntity);

        $this->serializer->expects($this->once())
            ->method('serialize')
            ->with($menuEntity, MenuSerializer::class)
            ->willReturn($menuJson);

        // Act
        $response = $this->menuController->show($menuId);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertSame($menuJson, $response->getContent());
    }

    /**
     * Tests the show action when the menu is not found.
     *
     * @return void
     */
    public function testShowActionNotFound(): void
    {
        // Arrange
        $this->menuRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        // Assert
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Menu item "1" not found');

        // Act
        $this->menuController->show(1);
    }

    /**
     * Test the update action.
     * 
     * @dataProvider menuDataProvider
     * 
     * @param MenuDto $menuDto The menu data transfer object
     * @param Menu $menu The menu entity
     * @param string $menuJson The menu as json
     *
     * @return void
     */
    public function testUpdateAction(MenuDto $menuDto, Menu $menu, string $menuJson): void
    {
        // Arrange
        $menuId = 1;
        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('getContent')
            ->willReturn($menuJson);

        $this->menuRepository->expects($this->once())
            ->method('find')
            ->with($menuId)
            ->willReturn($menu);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with($menuJson, MenuDto::class, 'json')
            ->willReturn($menuDto);
            
        $constraintViolationList = $this->createMock(ConstraintViolationListInterface::class);
        $this->validator->expects($this->once())
            ->method('validate')
            ->with($menuDto, null, ['create'])
            ->willReturn($constraintViolationList);

        $this->menuService->expects($this->once())
            ->method('update')
            ->with($menu, $menuDto)
            ->willReturn($menu);

        $this->serializer->expects($this->once())
            ->method('serialize')
            ->with($menu, 'json')
            ->willReturn($menuJson);

        // Act
        $response = $this->menuController->update($request, $menuId);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertSame($menuJson, $response->getContent());
    }

    /**
     * Tests the update action when the menu is not found.
     *
     * @return void
     */
    public function testUpdateActionNotFound(): void
    {
        // Arrange
        $menuId = 1;
        $request = $this->createMock(Request::class);
        $this->menuRepository->expects($this->once())
            ->method('find')
            ->with($menuId)
            ->willReturn(null);

        // Assert
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage(sprintf('Menu item "%d" not found', $menuId));

        // Act
        $this->menuController->update($request, $menuId);
    }

    /**
     * Tests the update action with validation errors.
     * 
     * @dataProvider menuDataProvider
     * 
     * @param MenuDto $menuDto The menu data transfer object
     * @param Menu $menu The menu entity
     * @param string $menuJson The menu as json
     *
     * @return void
     */
    public function testUpdateActionValidationFailed(MenuDto $menuDto, Menu $menu, string $menuJson): void
    {
        // Arrange
        $menuId = 1;
        $errors = [
            'errors' => [
                'name' => 'Name cannot be blank',
                'description' => 'Description cannot be longer than 255 characters'
            ]
        ];
       
        $violations = array_map(function ($key, $error) {
            return new ConstraintViolation($error, null, [], null, $key, null);
        }, array_keys($errors['errors']), $errors['errors']);
        
        $constraintViolationList = new ConstraintViolationList($violations);
        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('getContent')
            ->willReturn($menuJson);

        $this->menuRepository->expects($this->once())
            ->method('find')
            ->with($menuId)
            ->willReturn($menu);

        $this->serializer->expects($this->once())
            ->method('deserialize')
            ->with($menuJson, MenuDto::class, 'json')
            ->willReturn($menuDto);

        $this->validator->expects($this->once())
            ->method('validate')
            ->with($menuDto, null, ['create'])
            ->willReturn($constraintViolationList);

        // Act
        $response = $this->menuController->update($request, $menuId);

        // Assert
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertSame(json_encode($errors), $response->getContent());
    }

    /**
     * Provides a menu stub, entity and json string.
     *
     * @return array
     */
    public static function menuDataProvider(): array
    {
        $menuData = [
            'name' => 'New menu name',
            'description' => 'New menu description',
            'order' => 5
        ];

        return [
            [
                MenuDtoStub::create($menuData),
                MenuStub::create($menuData),
                json_encode($menuData)
            ]
        ];
    }

    /**
     * Provides the menu list data (entities and json encoded).
     *
     * @return array
     */
    public static function menusDataProvider(): array
    {
        $menus = [
            ['name' => 'Starters', 'description' => 'The starters description', 'order' => 1],
            ['name' => 'Mains', 'description' => 'The mains description', 'order' => 2],
            ['name' => 'Drinks', 'description' => 'The drinks description', 'order' => 3]
        ];

        return [
            [
                [
                    MenuStub::create($menus[0]),
                    MenuStub::create($menus[1]),
                    MenuStub::create($menus[2])
                ],
                json_encode($menus),
            ]
        ];
    }
}
