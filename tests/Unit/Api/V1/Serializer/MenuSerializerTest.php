<?php

declare(strict_types=1);

namespace App\Tests\Unit\Api\V1\Serializer;

use App\Api\V1\Entity\Menu;
use App\Api\V1\Serializer\MenuSerializer;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class MenuSerializerTest
 * 
 * Provides unit tests for the API v1 MenuSerializer.
 * 
 * @package App\Tests\Unit\Api\V1\Serializer
 * @author Stephen Speakman <hellospeakman@gmail.com>
 * 
 * @group api
 * @group api_v1
 * @group serializer
 */
class MenuSerializerTest extends TestCase
{
    /**
     * The serializer.
     *
     * @var SerializerInterface|MockObject|null
     */
    private SerializerInterface|MockObject|null $serializer;

    /**
     * The menu serializer.
     *
     * @var MenuSerializer|null
     */
    private MenuSerializer|null $menuSerializer;

    /**
     * Executed before each test case is run.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->menuSerializer = new MenuSerializer($this->serializer);
    }

    /**
     * Executed after each test case is run.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        $this->serializer = null;
        $this->menuSerializer = null;

        parent::tearDown();
    }

    /**
     * Test the deserializer method.
     *
     * @return void
     */
    public function testDeserialize(): void
    {
        // Arrange
        $menuData = [
            'name' => 'Test Menu',
            'description' => 'This is a test menu',
            'order' => 1
        ];

        $json = json_encode($menuData);
        $menu = new Menu();
        $menu->setName($menuData['name']);
        $menu->setDescription($menuData['description']);
        $menu->setOrder($menuData['order']);

        $this->serializer
            ->expects($this->once())
            ->method('deserialize')
            ->with($json, Menu::class, 'json', $this->isInstanceOf(DeserializationContext::class))
            ->willReturn($menu);

        // Act
        $deserializedMenu = $this->menuSerializer->deserialize($json, Menu::class);

        // Assert
        $this->assertInstanceOf(Menu::class, $deserializedMenu);
        $this->assertEquals($menu, $deserializedMenu);
        $this->assertEquals($menuData['name'], $deserializedMenu->getName());
        $this->assertEquals($menuData['description'], $deserializedMenu->getDescription());
        $this->assertEquals($menuData['order'], $deserializedMenu->getOrder());
    }

    /**
     * Test the deserializer method with invalid json.
     *
     * @return void
     */
    public function testDeserializeWithInvalidJson(): void
    {
        // Arrange
        $json = 'invalid_json';

        $this->serializer
            ->expects($this->once())
            ->method('deserialize')
            ->with($json, Menu::class, 'json', $this->isInstanceOf(DeserializationContext::class))
            ->willThrowException(new \RuntimeException('Invalid JSON'));

        // Assert
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid JSON');

        // Act
        $this->menuSerializer->deserialize($json, Menu::class);
    }

    /**
     * Test the deserializer method with an invalid type.
     *
     * @return void
     */
    public function testDeserializeWithInvalidType(): void
    {
        // Arrange
        $type = 'invalid_type';
        $json = json_encode([
            'name' => 'Test Menu',
            'description' => 'This is a test menu',
            'order' => 1
        ]);

        $this->serializer
            ->expects($this->once())
            ->method('deserialize')
            ->with($json, $type, 'json', $this->isInstanceOf(DeserializationContext::class))
            ->willThrowException(new \RuntimeException('Invalid type'));

        // Assert
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid type');

        // Act
        $this->menuSerializer->deserialize($json, $type);
    }

    /**
     * Test the serialize method.
     *
     * @return void
     */
    public function testSerialize(): void
    {
        // Arrange
        $menuData = [
            'name' => 'Test Menu',
            'description' => 'This is a test menu',
            'order' => 1
        ];

        $json = json_encode($menuData);
        $menu = new Menu();
        $menu->setName($menuData['name']);
        $menu->setDescription($menuData['description']);
        $menu->setOrder($menuData['order']);

        $this->serializer
            ->expects($this->once())
            ->method('serialize')
            ->with($menu, 'json', $this->isInstanceOf(SerializationContext::class))
            ->willReturn($json);

        // Act
        $serializedJson = $this->menuSerializer->serialize($menu);

        // Assert
        $this->assertJson($serializedJson);
        $this->assertEquals($json, $serializedJson);
        foreach ($menuData as $key => $value) {
            if ($key === 'order') {
                $this->assertStringContainsString(sprintf('"%s":%d', $key, $value), $serializedJson);
                continue;
            }

            $this->assertStringContainsString(sprintf('"%s":"%s"', $key, $value), $serializedJson);
        }
    }

    /**
     * Test the serialize method with an invalid object.
     *
     * @return void
     */
    public function testInvalidSerialize(): void
    {
        // Arrange
        $invalidObject = new \stdClass();
    
        // Assert
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('App\Api\V1\Serializer\MenuSerializer::serialize(): Argument #1 ($menu) must be of type App\Api\V1\Entity\Menu, stdClass given');
    
        // Act
        $this->menuSerializer->serialize($invalidObject);
    }
}
