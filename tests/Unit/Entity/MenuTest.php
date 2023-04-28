<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Menu;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class MenuTest
 * 
 * Provides unit tests for the API v1 Menu entity.
 * 
 * @package App\Tests\Unit\Entity
 * @author Stephen Speakman <hellospeakman@gmail.com>
 * 
 * @group api
 * @group entity
 */
class MenuTest extends KernelTestCase
{
    /**
     * The menu entity.
     *
     * @var Menu|null
     */
    private Menu|null $menu;

    /**
     * Executed before each test case is run.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->menu = new Menu();
    }

    /**
     * Executed after each test case is run.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        $this->menu = null;

        parent::tearDown();
    }

    /**
     * Tests the delete method.
     *
     * @return void
     */
    public function testDelete(): void
    {
        // Assert
        $this->assertFalse($this->menu->isDeleted());

        // Act
        $this->menu->delete();

        // Assert
        $this->assertTrue($this->menu->isDeleted());
    }

    /**
     * Tests the restore method.
     *
     * @return void
     */
    public function testRestore(): void
    {
        // Act
        $this->menu->delete();

        // Assert
        $this->assertTrue($this->menu->isDeleted());

        // Act
        $this->menu->restore();

        // Assert
        $this->assertFalse($this->menu->isDeleted());
    }

    /**
     * Tests the set created on persist method.
     *
     * @return void
     */
    public function testSetCreatedOnPersist(): void
    {
        // Assert
        $this->assertNull($this->menu->getCreated());

        // Act
        $this->menu->setCreatedOnPersist();

        // Assert
        $this->assertInstanceOf(DateTimeImmutable::class, $this->menu->getCreated());
    }

    /**
     * Tests the set description method.
     *
     * @return void
     */
    public function testSetDescription(): void
    {
        // Arrange
        $description = 'A test description for the menu entity.';

        // Act
        $this->menu->setDescription($description);
        
        // Assert
        $this->assertSame($description, $this->menu->getDescription());
    }

    /**
     * Tests the set modified on update method.
     *
     * @return void
     */
    public function testSetModifiedOnUpdate(): void
    {
        // Assert
        $this->assertNull($this->menu->getModified());

        // Act
        $this->menu->setModifiedOnUpdate();

        // Assert
        $this->assertInstanceOf(DateTimeImmutable::class, $this->menu->getModified());

        // Act
        $this->menu->setDescription(null);

        // Assert
        $this->assertNull($this->menu->getDescription());
    }

    /**
     * Tests the set name method.
     *
     * @return void
     */
    public function testSetName(): void
    {
        // Arrange
        $name = 'Test menu';

        // Act
        $this->menu->setName($name);

        // Assert
        $this->assertSame($name, $this->menu->getName());
    }

    /**
     * Tests the set order method.
     *
     * @return void
     */
    public function testSetOrder(): void
    {
        // Arrange
        $order = 1;

        // Act
        $this->menu->setOrder($order);

        // Assert
        $this->assertSame($order, $this->menu->getOrder());

        // Act
        $this->menu->setOrder(null);

        // Assert
        $this->assertNull($this->menu->getOrder());
    }
}
