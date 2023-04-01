<?php

namespace App\Contracts\Dto;

interface Dto
{
    /**
     * Returns an array of validation groups for a given operation.
     *
     * @return array The validation groups
     */
    public function getValidationGroups(): array;
}