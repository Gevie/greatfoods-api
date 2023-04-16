<?php

declare(strict_types=1);

namespace App\Tests\Unit\Api\V1\Service;

use App\Api\V1\Dto\MenuDto;
use App\Api\V1\Entity\Menu;
use App\Api\V1\Service\MenuService;
use App\Contracts\Repository\MenuRepository as MenuRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class MenuServiceTest
 * 
 * @package App\Tests\Unit\Api\V1\Service
 * @author Stephen Speakman <hellospeakman@gmail.com>
 * 
 * @group api
 * @group api_v1
 * @group service
 */
class MenuServiceTest extends KernelTestCase
{
    /**
     * The entity manager.
     *
     * @var EntityManagerInterface|MockObject|null
     */
    private EntityManagerInterface|MockObject|null $entityManager;

    /**
     * The menu repository.
     *
     * @var MenuRepositoryInterface|MockObject|null
     */
    private MenuRepositoryInterface|MockObject|null $menuRepository;

    /**
     * The menu service.
     *
     * @var MenuService|null
     */
    private MenuService|null $menuService;

    /**
     * Executed before each test case is run.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->menuRepository = $this->createMock(MenuRepositoryInterface::class);
        $this->menuService = new MenuService($this->entityManager, $this->menuRepository);
    }

    /**
     * Executed after each test case is run.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        $this->entityManager = null;
        $this->menuRepository = null;
        $this->menuService = null;

        parent::tearDown();
    }

    /**
     * Tests the create method with the save option.
     *
     * @dataProvider createDataProvider
     * 
     * @return void
     */
    public function testCreateWithSave(array $menuData): void
    {
        $menuDto = $this->createMock(MenuDto::class);
        $menuDto->name = $menuData['name'];
        $menuDto->description = $menuData['description'];
        $menuDto->order = $menuData['order'];

        $this->menuRepository->expects($this->once())
            ->method('save');

        // Act
        $menu = $this->menuService->create($menuDto, true);

        // Assert
        $this->assertInstanceOf(Menu::class, $menu);
        $this->assertEquals($menuData['name'], $menu->getName());
        $this->assertEquals($menuData['description'], $menu->getDescription());
        $this->assertEquals($menuData['order'], $menu->getOrder());
    }

    /**
     * Tests the create method without the save option.
     *
     * @dataProvider createDataProvider
     * 
     * @return void
     */
    public function testCreateWithoutSave(array $menuData): void
    {
        // Arrange
        $menuDto = $this->createMock(MenuDto::class);
        $menuDto->name = $menuData['name'];
        $menuDto->description = $menuData['description'];
        $menuDto->order = $menuData['order'];

        $this->menuRepository->expects($this->never())
            ->method('save');

        // Act
        $menu = $this->menuService->create($menuDto, false);

        // Assert
        $this->assertInstanceOf(Menu::class, $menu);
        $this->assertEquals($menuData['name'], $menu->getName());
        $this->assertEquals($menuData['description'], $menu->getDescription());
        $this->assertEquals($menuData['order'], $menu->getOrder());
    }

    /**
     * Tests the delete method.
     *
     * @return void
     */
    public function testDelete(): void
    {
        // Arrange
        $menu = $this->createMock(Menu::class);

        $menu->expects($this->once())
            ->method('delete');

        $this->menuRepository->expects($this->once())
            ->method('save');

        // Act
        $this->menuService->delete($menu);
    }

    /**
     * Tests the update method.
     *
     * @dataProvider updateDataProvider
     * 
     * @return void
     */
    public function testUpdate(array $originalData, array $updatedData): void
    {
        // Arrange
        $menu = new Menu();
        $menu->setName($originalData['name']);
        $menu->setDescription($originalData['description']);
        $menu->setOrder($originalData['order']);

        $menuDto = $this->createMock(MenuDto::class);
        $menuDto->name = $updatedData['name'];
        $menuDto->description = $updatedData['description'];
        $menuDto->order = $updatedData['order'];

        $this->menuRepository->expects($this->once())
            ->method('save')
            ->with($menu, true);

        // Act
        $updatedMenu = $this->menuService->update($menu, $menuDto);

        // Assert
        $this->assertInstanceOf(Menu::class, $updatedMenu);
        $this->assertEquals($updatedData['name'], $updatedMenu->getName());
        $this->assertEquals($updatedData['description'], $updatedMenu->getDescription());
        $this->assertEquals($updatedData['order'], $updatedMenu->getOrder());
    }

    /**
     * Provides data for the create tests.
     *
     * @return array
     */
    public static function createDataProvider(): array
    {
        return [
            [
                [
                    'name' => 'Starters',
                    'description' => 'A selection of starters.',
                    'order' => 1
                ]
            ],
            [
                [
                    'name' => 'Hot Wings',
                    'description' => '',
                    'order' => 2
                ]
            ],
            [
                [
                    'name' => 'Bundles',
                    'description' => 'Check out our bundles.',
                    'order' => null
                ]
            ]
        ];
    }

    /**
     * Provides data for the update tests.
     *
     * @return array
     */
    public static function updateDataProvider(): array
    {
        return [
            [
                [
                    'name' => 'Startrs',
                    'description' => '',
                    'order' => 1
                ],
                [
                    'name' => 'Starters',
                    'description' => 'A selection of starters',
                    'order' => 1
                ]
            ],
            [
                [
                    'name' => 'Hot Wings',
                    'description' => 'Freshly cooked spicy wings.',
                    'order' => 5
                ],
                [
                    'name' => 'Hot Wings',
                    'description' => 'Freshly cooked spicy wings.',
                    'order' => 2
                ]
            ]
        ];
    }
}
