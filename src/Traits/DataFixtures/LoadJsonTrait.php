<?php

declare(strict_types=1);

namespace App\Traits\DataFixtures;

use App\Entity\AbstractEntity;
use JsonException;
use RuntimeException;

/**
 * Trait LoadJsonTrait
 *
 * Used to load seed data from .json files and pass to Fixtures.
 *
 * @package App\Traits\DataFixtures
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
trait LoadJsonTrait
{
    /**
     * Loads data from a JSON file and returns an associative array of records.
     *
     * @param string $path The path to the JSON file
     *
     * @return AbstractEntity[] An array of records (multi-dimensional)
     */
    protected function loadFromJson(string $path): array
    {
        if (! file_exists($path)) {
            throw new RuntimeException(
                sprintf('Could not find the JSON file at path "%s".', $path)
            );
        }

        $json = (string) file_get_contents($path);

        try {
            /** @var AbstractEntity[] */
            $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new RuntimeException(sprintf(
                'Error decoding JSON data: "%s"',
                $exception->getMessage()
            ));
        }

        return $data;
    }
}
