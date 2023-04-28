<?php

declare(strict_types=1);

namespace App\Api\V1\Service;

use App\Api\V1\Dto\MenuDto;
use App\Entity\Menu;
use App\Api\V1\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class MenuService
 *
 * Provides a service for creating and managing menu entities.
 *
 * @package App\Api\V1\Service
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
     * @param MenuDto $menuDto The Menu Data Transfer Object
     * @param bool $save Whether to save the menu entity, default is true
     *
     * @return Menu The newly created menu entity
     */
    public function create(MenuDto $menuDto, bool $save = true): Menu
    {
        $menu = new Menu();
        $menu->setName($menuDto->name);
        $menu->setDescription($menuDto->description);
        $menu->setOrder($menuDto->order);

        if ($save) {
            $this->menuRepository->save($menu, true);
        }

        return $menu;
    }

    /**
     * Soft deletes a menu entity.
     *
     * @param Menu $menu The menu entity
     *
     * @return void
     */
    public function delete(Menu $menu): void
    {
        $this->menuRepository->remove($menu, true);
    }

    /**
     * Updates a menu entity with the given name, description and order.
     *
     * @param Menu $menu The menu entity to update
     * @param MenuDto $menuDto The Menu Data Transfer Object
     *
     * @return Menu The newly updated menu entity
     */
    public function update(Menu $menu, MenuDto $menuDto): Menu
    {
        $menu->setName($menuDto->name);
        $menu->setDescription($menuDto->description);
        $menu->setOrder($menuDto->order);

        $this->menuRepository->save($menu, true);

        return $menu;
    }
}
