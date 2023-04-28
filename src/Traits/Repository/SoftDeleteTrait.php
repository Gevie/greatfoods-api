<?php

declare(strict_types=1);

namespace App\Traits\Repository;

use App\Contracts\Entity\SoftDeletable as SoftDeletableInterface;
use App\Entity\AbstractEntity;

trait SoftDeleteTrait
{
    /**
     * Removes an entity from the database using soft delete.
     *
     * @param SoftDeletableInterface|AbstractEntity $entity The entity to remove
     * @param bool $flush Whether to flush the changes to the database immediately, default is false
     *
     * @return void
     */
    public function remove(SoftDeletableInterface $entity, bool $flush = false): void
    {
        $entity->delete();
        $this->save($entity, $flush);
    }
}
