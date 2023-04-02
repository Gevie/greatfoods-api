<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Api\V1\Serializer\MenuSerializer;
use App\Traits\DataFixtures\LoadJsonTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use JMS\Serializer\SerializerBuilder;
use RuntimeException;

/**
 * Class MenuFixtures
 *
 * @package App\DataFixtures
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
class MenuFixtures extends Fixture
{
    use LoadJsonTrait;

    private const JSON_FILE_PATH = __DIR__ . '/json/menus.json';

    /**
     * Loads menu data fixtures from a JSON file.
     *
     * @param ObjectManager $manager The entity manager
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $menuArray = $this->loadFromJson(self::JSON_FILE_PATH);

        $serializer = SerializerBuilder::create()->build();
        $menuSerializer = new MenuSerializer($serializer);

        $menus = array_map(function ($menu) use ($menuSerializer) {
            $menuJson = json_encode($menu);
            if (! $menuJson) {
                throw new RuntimeException('Could not encode entity to json');
            }

            return $menuSerializer->deserialize($menuJson);
        }, $menuArray);

        foreach ($menus as $menu) {
            $manager->persist($menu);
        }

        $manager->flush();
    }
}
