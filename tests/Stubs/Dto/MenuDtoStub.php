<?php

declare(strict_types=1);

namespace App\Tests\Stubs\Dto;

use App\Api\V1\Dto\MenuDto;

/**
 * Class MenuDtoStub
 * 
 * Provides a stub for creating MenuDto objects.
 * 
 * @package App\Tests\Stubs\Dto
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
final class MenuDtoStub
{
    /**
     * The default values 
     */
    private const MENU_DTO_DEFAULTS = [
        'name' => 'Menu name',
        'description' => 'The menu description',
        'order' => 1
    ];

    /**
     * Create an instance of the MenuDto.
     *
     * @param array $data The data to override
     * 
     * @return MenuDto The menu
     */
    public static function create(array $data = []): MenuDto
    {
        $menuDtoData = array_merge(MenuDtoStub::MENU_DTO_DEFAULTS, $data);
        
        $menuDto = new MenuDto();
        $menuDto->name = $menuDtoData['name'];
        $menuDto->description = $menuDtoData['description'];
        $menuDto->order = $menuDtoData['order'];

        return $menuDto;
    }
}
