<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\AbstractEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class AbstractRepository
 * 
 * The abstract repository class with shared functionality.
 * 
 * @package App\Repository
 * @author Stephen Speakman <hellospeakman@gmail.com>
 * 
 * @extends ServiceEntityRepository<AbstractEntity>
 *
 * @method AbstractEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method AbstractEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method AbstractEntity[]    findAll()
 * @method AbstractEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
abstract class AbstractRepository extends ServiceEntityRepository
{
    /**
     * AbstractRepository constructor
     *
     * @param ManagerRegistry $registry The manager registry
     * @param string $entityClass The entity class name
     */
    public function __construct(ManagerRegistry $registry, string $entityClass)
    {
        parent::__construct($registry, $entityClass);
    }
    
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
     * Persists a Menu entity in the database.
     *
     * @param AbstractEntity $menu The Menu entity to persist
     * @param bool $flush Whether to flush the EntityManager after persisting the entity. Default is false
     *
     * @return void
     */
    public function save(AbstractEntity $menu, bool $flush = false): void
    {
        $this->getEntityManager()->persist($menu);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
