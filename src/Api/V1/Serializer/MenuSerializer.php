<?php

declare(strict_types=1);

namespace App\Api\V1\Serializer;

use App\Api\V1\Entity\Menu;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;

/**
 * Class MenuSerializer
 *
 * Serializes and deserializes Menu entities to and from JSON format.
 *
 * @package App\Api\V1\Serializer
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
     * Deserializes a JSON string into a Menu object.
     *
     * @param string $json The JSON string to deserialize
     * @param string|null $type The type of object to create. Default is null, the type will be inferred from the JSON.
     *
     * @return Menu The deserialized Menu object
     */
    public function deserialize(string $json, ?string $type = null): Menu
    {
        $context = DeserializationContext::create()
            ->setGroups(['menu'])
            ->setVersion('1.0');

        /** @var Menu */
        return $this->serializer->deserialize($json, $type ?? Menu::class, 'json', $context);
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
