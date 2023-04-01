<?php

declare(strict_types=1);

namespace App\Api\V1\Serializer;

use App\Api\V1\Entity\Menu;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;

/**
 * Class MenuSerializer.
 * 
 * @package App\Serializer
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
class MenuSerializer
{
    /**
     * MenuSerializer constructor.
     *
     * @param SerializerInterface $serializer The serializer
     */
    public function __construct(protected SerializerInterface $serializer)
    {
        // ...
    }

    /**
     * Serializes a Menu object into a JSON string.
     *
     * @param Menu $menu The menu entity
     * 
     * @return string The JSON string
     */
    public function serialize(Menu $menu): string
    {
        $context = SerializationContext::create()
            ->setSerializeNull(true)
            ->setGroups(['menu'])
            ->setVersion('1.0');

        return $this->serializer->serialize($menu, 'json', $context);
    }
}