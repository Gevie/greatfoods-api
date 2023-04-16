<?php

declare(strict_types=1);

namespace App\Tests\Traits\DataFixtures;

use App\Traits\DataFixtures\LoadJsonTrait;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Class LoadJsonTraitTest
 * 
 * @package App\Tests\Traits\DataFixtures
 * @author Stephen Speakman <hellospeakman@gmail.com>
 * 
 * @group core
 * @group trait
 * @group fixture
 */
class LoadJsonDataTest extends TestCase
{
    use LoadJsonTrait;

    private const VALID_JSON_PATH = __DIR__ . '/test_valid_data.json';
    private const INVALID_JSON_PATH = __DIR__ . '/test_invalid_data.json';

    /**
     * Test the loadFromJson method with valid data.
     *
     * @return void
     */
    public function testLoadFromJsonWithValidData(): void
    {
        // Act
        $data = $this->loadFromJson(self::VALID_JSON_PATH);

        // Assert
        $this->assertIsArray($data);
        $this->assertCount(2, $data);
    }

    /**
     * Test the loadFromJson method with an invalid path.
     *
     * @return void
     */
    public function testLoadFromJsonWithInvalidFile(): void
    {
        $this->expectException(RuntimeException::class);
        $this->loadFromJson('/invalid/file/path.json');
    }

    /**
     * Test the loadFromJson method with invalid data.
     *
     * @return void
     */
    public function testLoadFromJsonWithInvalidData(): void
    {
        $this->expectException(RuntimeException::class);
        $this->loadFromJson(self::INVALID_JSON_PATH);
    }
}
