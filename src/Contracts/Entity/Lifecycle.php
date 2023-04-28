<?php

namespace App\Contracts\Entity;

/**
 * Interface Lifecycle
 * 
 * An interface to handle the lifecycle of an entity.
 * 
 * @package App\Contracts\Entity
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
interface Lifecycle
{
    /**
     * Soft deletes the user item.
     *
     * @return void
     */
    public function delete(): void;

    /**
     * Gets the created timestamp of the user item.
     *
     * @return \DateTimeInterface|null The created timestamp or null
     */
    public function getCreated(): ?\DateTimeInterface;

    /**
     * Gets the deleted timestamp of the user item.
     *
     * @return \DateTimeInterface|null The deleted timestamp or null
     */
    public function getDeleted(): ?\DateTimeInterface;
    
    /**
     * Gets the modified timestamp of the user item.
     *
     * @return \DateTimeInterface|null The modified timestamp or null
     */
    public function getModified(): ?\DateTimeInterface;

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

    /**
     * Sets the created timestamp of the user item to the current time on persist.
     *
     * @return void
     */
    public function setCreatedOnPersist(): void;

    /**
     * Sets the modified timestamp of the user item to the current time on persist.
     *
     * @return void
     */
    public function setModifiedOnUpdate(): void;
}
