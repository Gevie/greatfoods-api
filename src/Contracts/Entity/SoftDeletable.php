<?php

namespace App\Contracts\Entity;

use DateTimeInterface;

/**
 * Interface SoftDeletable
 * 
 * An interface to handle soft deletion and restoration.
 * 
 * @package App\Contracts\Entity
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
interface SoftDeletable
{
    /**
     * Soft deletes the user item.
     *
     * @return void
     */
    public function delete(): void;

    /**
     * Gets the deleted timestamp of the user item.
     *
     * @return DateTimeInterface|null The deleted timestamp or null
     */
    public function getDeleted(): ?DateTimeInterface;

    /**
     * Determines whether the user item has been deleted.
     *
     * @return boolean True if deleted, false otherwise
     */
    public function isDeleted(): bool;

    /**
     * Restores a deleted user item.
     *
     * @return void
     */
    public function restore(): void;
}
