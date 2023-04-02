<?php

namespace App\Contracts\Dto;

/**
 * Interface Dto
 *
 * A generic Data Transfer Object (DTO) interface for moving data around the system.
 *
 * @package App\Contracts\Dto
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
interface Dto
{
    /**
     * Returns an array of validation groups for a given operation.
     *
     * @return array<string> The validation groups
     */
    public function getValidationGroups(): array;
}
