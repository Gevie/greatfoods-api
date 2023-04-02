<?php

declare(strict_types=1);

namespace App\Traits\DataFixtures;

use App\Entity\AbstractEntity;
use JsonException;
use RuntimeException;

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
        $json = file_get_contents($path);
        if (! $json) {
            throw new RuntimeException(
                sprintf('Could not open the JSON file at path "%s".', $path)
            );
        }

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
