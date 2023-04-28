<?php

declare(strict_types=1);

namespace App\Api\V1\Repository;

use App\Contracts\Repository\SoftDelete as SoftDeleteInterface;
use App\Entity\Menu;
use App\Repository\AbstractRepository;
use App\Traits\Repository\SoftDeleteTrait;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class MenuRepository
 *
 * This class is a Doctrine repository for managing Menu entities in the application.
 *
 * @package App\Api\V1\Repository
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
class MenuRepository extends AbstractRepository implements SoftDeleteInterface
{
    use SoftDeleteTrait;

    /**
     * MenuRepository constructor.
     *
     * @param ManagerRegistry $registry The manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }
}
