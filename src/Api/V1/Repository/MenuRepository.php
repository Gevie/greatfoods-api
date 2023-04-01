<?php

declare(strict_types=1);

namespace App\Api\V1\Repository;

use App\Api\V1\Entity\Menu;
use App\Contracts\Entity\Menu as MenuInterface;
use App\Contracts\Repository\MenuRepository as MenuRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class MenuRepository
 * 
 * @package App\Repository
 * @author Stephen Speakman <hellospeakman@gmail.com>
 * 
 * @extends ServiceEntityRepository<Menu>
 *
 * @method Menu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Menu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Menu[]    findAll()
 * @method Menu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuRepository extends ServiceEntityRepository implements MenuRepositoryInterface
{
    /**
     * MenuRepository constructor.
     *
     * @param ManagerRegistry $registry The manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    /**
     * Permanently removes a menu item from the database.
     *
     * @param MenuInterface $menu The menu item to remove permanently.
     * @param bool $flush Whether to flush the changes to the database immediately.
     * 
     * @return void
     */
    public function permanentlyRemove(MenuInterface $menu, bool $flush = false): void
    {
        $this->getEntityManager()->remove($menu);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Removes a Menu entity from the database using soft delete.
     *
     * @param MenuInterface $menu The Menu entity to remove.
     * @param bool $flush Whether to flush the EntityManager after removing the entity. Default is false.
     *
     * @return void
     */
    public function remove(MenuInterface $menu, bool $flush = false): void
    {
        $menu->delete();
        $this->save($menu, $flush);
    }

    /**
     * Persists a Menu entity in the database.
     *
     * @param MenuInterface $menu The Menu entity to persist.
     * @param bool $flush Whether to flush the EntityManager after persisting the entity. Default is false.
     *
     * @return void
     */
    public function save(MenuInterface $menu, bool $flush = false): void
    {
        $this->getEntityManager()->persist($menu);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
