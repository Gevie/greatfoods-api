<?php

declare(strict_types=1);

namespace App\Tests\Behat\Unit;

use App\Api\V1\Service\MenuService;
use App\Tests\Behat\ApiMenuContext;
use App\Tests\Stubs\Dto\MenuDtoStub;
use App\Tests\Stubs\Entity\MenuStub;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class ApiMenuContextTest
 * 
 * Tests the ApiMenuContext class for running menu related behat tests.
 * 
 * @package App\Tests\Behat\Unit
 * @author Stephen Speakman <hellospeakman@gmail.com>
 * 
 * @group behat
 * @group behat_api
 * @group behat_menu
 * @group context
 */
class ApiMenuContextTest extends KernelTestCase
{
    /**
     * The entity manager.
     *
     * @var EntityManagerInterface|MockObject|null
     */
    private EntityManagerInterface|MockObject|null $entityManager;

    /**
     * The ApiMenuContext.
     *
     * @var ApiMenuContext|MockObject|null
     */
    private ApiMenuContext|MockObject|null $context;

    /**
     * The HTTP client.
     *
     * @var KernelBrowser|MockObject|null
     */
    private KernelBrowser|MockObject|null $client;

    /**
     * The kernel.
     *
     * @var KernelInterface|MockObject|null
     */
    private KernelInterface|MockObject|null $kernelMock;

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
     * Executed before each test case is run.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->client = $this->createMock(KernelBrowser::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->kernelMock = $this->createMock(KernelInterface::class);
        $this->menuService = $this->createMock(MenuService::class);
        $this->serializer = $this->createMock(SerializerInterface::class);

        $this->context = $this->getMockForAbstractClass(ApiMenuContext::class, [
            $this->client,
            $this->kernelMock,
            $this->entityManager,
            $this->menuService,
            $this->serializer
        ]);
    }

    /**
     * Executed after each test case is run.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        $this->client = null;
        $this->context = null;
        $this->entityManager = null;
        $this->kernelMock = null;
        $this->menuService = null;
        $this->serializer = null;
    }

    /**
     * Tests "theFollowingMenusExist" context action.
     *
     * @return void
     */
    public function testTheFollowingMenusExist(): void
    {
        // Arrange
        $tableNode = $this->getMockBuilder(TableNode::class)
            ->disableOriginalConstructor()
            ->getMock();

        $tableNode->method('getIterator')
            ->willReturn(new \ArrayIterator([
                ['name' => 'Menu 1', 'description' => 'Description 1', 'order' => 1],
                ['name' => 'Menu 2', 'description' => 'Description 2', 'order' => 2],
                ['name' => 'Menu 3', 'description' => 'Description 3', 'order' => 3],
            ]));

        $menuDtos = [];
        $menus = [];
        foreach ($tableNode as $menuData) {
            $menuDtos[] = MenuDtoStub::create($menuData);
            $menus[] = MenuStub::create($menuData);
        }

        $this->serializer->expects($this->exactly(3))
            ->method('deserialize')
            ->willReturnOnConsecutiveCalls(...$menuDtos);

        $this->menuService->expects($this->exactly(3))
            ->method('create')
            ->willReturnOnConsecutiveCalls(...$menus);

        $this->entityManager->expects($this->exactly(3))
            ->method('persist');

        $this->entityManager->expects($this->once())
            ->method('flush');

        // Act
        $this->context->theFollowingMenusExist($tableNode);
    }
}
