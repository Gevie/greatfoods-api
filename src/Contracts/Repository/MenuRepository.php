<?php

namespace App\Contracts\Repository;

use App\Entity\AbstractEntity;

/**
 * Interface MenuRepository
 *
 * This interface is a Doctrine repository for managing Menu entities in the application.
 *
 * @package App\Contracts\Repository
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
interface MenuRepository
{
    /**
     * Permanently removes a menu item from the database.
     *
     * @param AbstractEntity $menu The menu item to remove permanently.
     * @param bool $flush Whether to flush the changes to the database immediately.
     *
     * @return void
     */
    public function permanentlyRemove(AbstractEntity $menu, bool $flush = false): void;

    /**
     * Removes a Menu entity from the database using soft delete.
     *
     * @param AbstractEntity $menu The Menu entity to remove.
     * @param bool $flush Whether to flush the EntityManager after removing the entity. Default is false.
     *
     * @return void
     */
    public function remove(AbstractEntity $menu, bool $flush = false): void;

    /**
     * Persists a Menu entity in the database.
     *
     * @param AbstractEntity $menu The Menu entity to persist.
     * @param bool $flush Whether to flush the EntityManager after persisting the entity. Default is false.
     *
     * @return void
     */
    public function save(AbstractEntity $menu, bool $flush = false): void;
}
