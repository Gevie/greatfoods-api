<?php

declare(strict_types=1);

namespace App\Api\V1\Repository;

use App\Entity\User;
use App\Repository\AbstractRepository;
use App\Traits\Repository\SoftDeleteTrait;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class UserRepository
 *
 * This class is a Doctrine repository for managing User entities in the application.
 *
 * @package App\Repository
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
class UserRepository extends AbstractRepository
{
    use SoftDeleteTrait;

    /**
     * UserRepository constructor.
     *
     * @param ManagerRegistry $registry The manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
}
