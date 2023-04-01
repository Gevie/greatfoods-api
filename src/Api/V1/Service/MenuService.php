<?php

declare(strict_types=1);

namespace App\Api\V1\Service;

use App\Api\V1\Dto\MenuDto;
use App\Api\V1\Entity\Menu;
use App\Api\V1\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class MenuService
 * 
 * @package App\Service
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
class MenuService
{
    /**
     * MenuService constructor.
     *
     * @param EntityManagerInterface $entityManager The entity manager
     * @param MenuRepository $menuRepository The menu repository
     */
    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected MenuRepository $menuRepository
    ) {
        // ...
    }

    /**
     * Creates a new menu entity with the given name, description and order.
     *
     * @param MenuDto The Menu Data Transfer Object
     * 
     * @return Menu The newly created menu entity
     */
    public function create(MenuDto $menuDto): Menu
    {
        $menu = new Menu();
        $menu->setName($menuDto->name);
        $menu->setDescription($menuDto->description);
        $menu->setOrder($menuDto->order);

        $this->menuRepository->save($menu, true);

        return $menu;
    }
}