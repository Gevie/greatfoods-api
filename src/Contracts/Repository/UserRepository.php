<?php

namespace App\Contracts\Repository;

use App\Contracts\Entity\User as UserInterface;

/**
 * Interface UserRepository
 *
 * This interface is a Doctrine repository for managing user entities in the application.
 *
 * @package App\Contracts\Repository
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
interface UserRepository
{
    /**
     * Permanently removes a user item from the database.
     *
     * @param UserInterface $User The user item to remove permanently.
     * @param bool $flush Whether to flush the changes to the database immediately.
     *
     * @return void
     */
    public function permanentlyRemove(UserInterface $User, bool $flush = false): void;

    /**
     * Removes a user entity from the database using soft delete.
     *
     * @param UserInterface $User The user entity to remove.
     * @param bool $flush Whether to flush the EntityManager after removing the entity. Default is false.
     *
     * @return void
     */
    public function remove(UserInterface $User, bool $flush = false): void;

    /**
     * Persists a user entity in the database.
     *
     * @param UserInterface $User The user entity to persist.
     * @param bool $flush Whether to flush the EntityManager after persisting the entity. Default is false.
     *
     * @return void
     */
    public function save(UserInterface $User, bool $flush = false): void;
}
