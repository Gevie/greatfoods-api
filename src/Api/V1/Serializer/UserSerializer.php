<?php

declare(strict_types=1);

namespace App\Api\V1\Serializer;

use App\Entity\User;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;

/**
 * Class UserSerializer
 *
 * Serializes and deserializes User entities to and from JSON format.
 *
 * @package App\Api\V1\Serializer
 * @author Stephen Speakman <hellospeakman@gmail.com>
 *
 * @Serializer\ExclusionPolicy("all")
 * @Serializer\SerializedName("user")
 */
class UserSerializer
{
    /**
     * UserSerializer constructor.
     *
     * @param SerializerInterface $serializer The serializer
     */
    public function __construct(protected SerializerInterface $serializer)
    {
        // ...
    }

    /**
     * Deserializes a JSON string into a user object.
     *
     * @param string $json The JSON string to deserialize
     * @param string|null $type The type of object to create. Default is null, the type will be inferred from the JSON.
     *
     * @return User The deserialized user object
     */
    public function deserialize(string $json, ?string $type = null): User
    {
        $context = DeserializationContext::create()
            ->setGroups(['user'])
            ->setVersion('1.0');

        /** @var User */
        return $this->serializer->deserialize($json, $type ?? User::class, 'json', $context);
    }

    /**
     * Serializes a user object into a JSON string.
     *
     * @param User $user The user entity
     *
     * @return string The JSON string
     */
    public function serialize(User $user): string
    {
        $context = SerializationContext::create()
            ->setSerializeNull(true)
            ->setGroups(['user'])
            ->setVersion('1.0');

        return $this->serializer->serialize($user, 'json', $context);
    }
}
