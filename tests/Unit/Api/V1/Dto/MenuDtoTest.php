<?php

declare(strict_types=1);

namespace App\Tests\Unit\Api\V1\Dto;

use App\Api\V1\Dto\MenuDto;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class MenuDtoTest
 * 
 * Provides unit tests for the API v1 MenuDto object.
 * 
 * @package App\Tests\Unit\Api\V1\Dto
 * @author Stephen Speakman <hellospeakman@gmail.com>
 * 
 * @group api
 * @group api_v1
 * @group dto
 */
class MenuDtoTest extends KernelTestCase
{
    /**
     * The validator.
     *
     * @var ValidatorInterface|null
     */
    private ValidatorInterface|null $validator;

    /**
     * Executed before each test case is run.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();
    }

    /**
     * Executed after each test case is run.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        $this->validator = null;

        parent::tearDown();
    }

    /**
     * Test the validation rules.
     *
     * @return void
     */
    public function testValidationErrors(): void
    {
        // Arrange
        $menuDto = new MenuDto();
        $menuDto->name = '';
        $menuDto->description = str_repeat('a', 256);
        $menuDto->order = -3;

        $menuDto2 = new MenuDto();
        $menuDto2->name = str_repeat('a', 129);

        // Act
        $errors = $this->validator->validate($menuDto);
        $errors2 = $this->validator->validate($menuDto2);

        // Assert
        $this->assertCount(3, $errors);
        $this->assertSame('Name cannot be blank', $errors->get(0)->getMessage());
        $this->assertSame('Description cannot be longer than 255 characters', $errors->get(1)->getMessage());

        $this->assertCount(1, $errors2);
        $this->assertSame('Name cannot be longer than 128 characters', $errors2->get(0)->getMessage());
    }
}
