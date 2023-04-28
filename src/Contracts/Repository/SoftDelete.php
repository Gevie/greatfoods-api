<?php

namespace App\Contracts\Repository;

use App\Contracts\Entity\SoftDeletable as SoftDeletableInterface;

interface SoftDelete
{
    /**
     * Removes an entity from the database using soft delete.
     *
     * @param SoftDeletableInterface $entity The entity to remove
     * @param bool $flush Whether to flush the changes to the database immediately, default is false
     *
     * @return void
     */
    public function remove(SoftDeletableInterface $entity, bool $flush = false): void;
}
