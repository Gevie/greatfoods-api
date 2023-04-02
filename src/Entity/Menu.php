<?php

declare(strict_types=1);

namespace App\Entity;

use App\Contracts\Entity\Menu as MenuInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Menu
 *
 * Represents a menu item in the application.
 *
 * @package App\Entity
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
#[ORM\HasLifecycleCallbacks()]
#[ORM\Table(name: 'menus')]
abstract class Menu extends AbstractEntity implements MenuInterface
{
    // ...
}
