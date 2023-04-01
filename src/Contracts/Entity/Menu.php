<?php

namespace App\Contracts\Entity;

/**
 * Interface Menu
 * 
 * Represents a menu item in the application.
 * 
 * @package App\Contracts\Entity
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
interface Menu
{
    /**
     * Soft deletes the menu item.
     *
     * @return void
     */
    public function delete(): void;

    /**
     * Gets the created timestamp of the menu item.
     *
     * @return \DateTimeInterface|null The created timestamp or null
     */
    public function getCreated(): ?\DateTimeInterface;

    /**
     * Gets the deleted timestamp of the menu item.
     *
     * @return \DateTimeInterface|null The deleted timestamp or null
     */
    public function getDeleted(): ?\DateTimeInterface;

    /**
     * Gets the id of the menu item.
     *
     * @return integer|null The menu id or null
     */
    public function getId(): ?int;

    /**
     * Gets the modified timestamp of the menu item.
     *
     * @return \DateTimeInterface|null The modified timestamp or null
     */
    public function getModified(): ?\DateTimeInterface;

    /**
     * Determines whether the menu item has been deleted.
     *
     * @return boolean True if deleted, false otherwise
     */
    public function isDeleted(): bool;

    /**
     * Restores a deleted menu item.
     *
     * @return void
     */
    public function restore(): void;

    /**
     * Sets the created timestamp of the menu item to the current time on persist.
     *
     * @return void
     */
    public function setCreatedOnPersist(): void;

    /**
     * Sets the modified timestamp of the menu item to the current time on persist.
     *
     * @return void
     */
    public function setModifiedOnUpdate(): void;
}