<?php

declare(strict_types=1);

namespace App\Traits\Repository;

use App\Contracts\Entity\Lifecycle as LifecycleInterface;
use App\Entity\AbstractEntity;
use BadMethodCallException;

trait SoftDeleteTrait
{
    /**
     * Permanently removes an entity from the database.
     *
     * @param AbstractEntity $entity The entity to remove permanently
     * @param bool $flush Whether to flush the changes to the database immediately, default is false
     *
     * @return void
     */
    public function permanentlyRemove(AbstractEntity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Removes an entity from the database using soft delete.
     *
     * @param LifecycleInterface $entity The entity to remove
     * @param bool $flush Whether to flush the changes to the database immediately, default is false
     *
     * @return void
     */
    public function remove(LifecycleInterface $entity, bool $flush = false): void
    {
        $entity->delete();
        $this->save($entity, $flush);
    }
}
