<?php

declare(strict_types=1);

namespace App\Tests\Unit\Filter\Doctrine\ORM;

use App\Filter\Doctrine\ORM\SoftDeleteFilter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class SoftDeleteFilterTest
 * 
 * @package App\Tests\Unit\Filter\Doctrine\ORM
 * @author Stephen Speakman <hellospeakman@gmail.com>
 * 
 * @group core
 * @group doctrine
 * @group filter
 */
class SoftDeleteFilterTest extends KernelTestCase
{
    /**
     * The entity manager.
     *
     * @var EntityManagerInterface|null
     */
    private EntityManagerInterface|null $entityManager;

    /**
     * The metadata.
     *
     * @var ClassMetadata|null
     */
    private ClassMetadata|null $metadata;

    /**
     * Executed before each test case is run.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->metadata = $this->createMock(ClassMetadata::class);
    }

    /**
     * Executed after each test case is run.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        $this->metadata = null;

        parent::tearDown();
    }

    /**
     * Test the constraint when a deleted field is present.
     *
     * @return void
     */
    public function testAddFilterConstraintWithDeletedField(): void
    {
        // Arrange
        $filter = new SoftDeleteFilter($this->entityManager);
        $this->metadata->expects($this->once())
            ->method('hasField')
            ->with('deleted')
            ->willReturn(true);

        // Act
        $result = $filter->addFilterConstraint($this->metadata, 'test_table');

        // Assert
        $this->assertSame(
            'test_table.deleted IS NULL OR test_table.deleted > CURRENT_TIMESTAMP()',
            $result
        );
    }

    /**
     * Test the constraint when a deleted field is not present.
     *
     * @return void
     */
    public function testAddFilterConstraintWithoutDeletedField(): void
    {
        // Arrange
        $filter = new SoftDeleteFilter($this->entityManager);
        $this->metadata->expects($this->once())
            ->method('hasField')
            ->with('deleted')
            ->willReturn(false);

        // Act
        $result = $filter->addFilterConstraint($this->metadata, 'test_table');

        // Assert
        $this->assertEmpty($result);
    }
}
