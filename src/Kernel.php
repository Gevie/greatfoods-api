<?php

declare(strict_types=1);

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

/**
 * Class Kernel
 * 
 * The application kernel.
 * 
 * @package App
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
class Kernel extends BaseKernel
{
    use MicroKernelTrait;
}
