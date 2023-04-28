<?php

declare(strict_types=1);

namespace App\Tests\Unit\Api\V1\Repository;

use App\Entity\User;
use App\Api\V1\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class MenuRepositoryTest
 * 
 * Provides unit tests for the API v1 MenuRepository.
 * 
 * @package App\Tests\Unit\Api\V1\Repository
 * @author Stephen Speakman <hellospeakman@gmail.com>
 * 
 * @group api
 * @group api_v1
 * @group repository
 */

class UserRepositoryTest extends KernelTestCase
{
    /**
     * The class metadata.
     *
     * @var ClassMetadata|MockObject|null
     */
    private ClassMetadata|MockObject|null $classMetadata;

    /**
     * The entity manager.
     *
     * @var EntityManagerInterface|MockObject|null
     */
    private EntityManagerInterface|MockObject|null $entityManager;

    /**
     * The manager registry.
     *
     * @var ManagerRegistry|MockObject|null
     */
    private ManagerRegistry|MockObject|null $registry;

    /**
     * Executed before each test case is run.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->classMetadata = $this->createMock(ClassMetadata::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->registry = $this->createMock(ManagerRegistry::class);

        $this->entityManager->expects($this->once())
            ->method('getClassMetadata')
            ->with(User::class)
            ->willReturn($this->classMetadata);

        $this->registry->expects($this->once())
            ->method('getManagerForClass')
            ->with(User::class)
            ->willReturn($this->entityManager);
    }

    /**
     * Executed after each test case is run.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        $this->classMetadata = null;
        $this->entityManager = null;
        $this->registry = null;
        
        parent::tearDown();
    }

    /**
     * Test the permanently remove method.
     *
     * @return void
     */
    public function testPermanentlyRemove(): void
    {
        // Arrange
        $user = $this->createMock(User::class);
        $userRepository = new UserRepository($this->registry);

        // Act
        $userRepository->permanentlyRemove($user, true);
    }

    /**
     * Test the soft delete remove method.
     *
     * @return void
     */
    public function testRemove(): void
    {
        // Arrange
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('delete');

        $userRepository = new UserRepository($this->registry);

        // Act
        $userRepository->remove($user, true);
    }

    /**
     * Test the save method.
     *
     * @return void
     */
    public function testSave(): void
    {
        // Arrange
        $user = $this->createMock(User::class);
        $userRepository = new UserRepository($this->registry);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($user);

        $this->entityManager->expects($this->once())
            ->method('flush');

        // Act
        $userRepository->save($user, true);
    }

    /**
     * Test the save method without flush.
     *
     * @return void
     */
    public function testSaveWithoutFlush(): void
    {
        // Arrange
        $user = $this->createMock(User::class);
        $userRepository = new UserRepository($this->registry);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($user);

        $this->entityManager->expects($this->never())
            ->method('flush');

        // Act
        $userRepository->save($user);
    }
}
