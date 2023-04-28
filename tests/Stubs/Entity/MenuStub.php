<?php

declare(strict_types=1);

namespace App\Tests\Stubs\Entity;

use App\Entity\Menu;

/**
 * Class MenuStub
 * 
 * Provides a stub for creating Menu objects.
 * 
 * @package App\Tests\Stubs\Entity
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
final class MenuStub
{
    /**
     * The default values 
     */
    private const MENU_DEFAULTS = [
        'name' => 'Menu name',
        'description' => 'The menu description',
        'order' => 1
    ];

    /**
     * Create an instance of the Menu entity.
     *
     * @param array $data The data to override
     * 
     * @return Menu The menu
     */
    public static function create(array $data = []): Menu
    {
        $menuData = array_merge(MenuStub::MENU_DEFAULTS, $data);
        
        $menu = new Menu();
        $menu->setName($menuData['name']);
        $menu->setDescription($menuData['description']);
        $menu->setOrder($menuData['order']);

        return $menu;
    }
}
