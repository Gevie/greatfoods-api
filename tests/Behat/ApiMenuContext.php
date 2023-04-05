<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Api\V1\Dto\MenuDto;
use App\Api\V1\Service\MenuService;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class ApiMenuContext
 * 
 * Adds specific context actions for testing the /api/v1/menus related endpoints.
 * 
 * @package App\Behat\Context
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
class ApiMenuContext extends ApiContext implements Context
{
    /**
     * ApiMenuContext contructor.
     *
     * @param MenuService $menuService The menu service
     * @param SerializerInterface $serializer The serializer
     */
    public function __construct(
        // private HttpClientInterface $httpClient,
        // private KernelInterface $kernel,
        private EntityManagerInterface $entityManager,
        private MenuService $menuService,
        private SerializerInterface $serializer
    ) {
        // parent::__construct($httpClient, $kernel);
    }

    /**
     * Save menus to the test database.
     * 
     * @Given the following menus exist:
     *
     * @param TableNode $menus The menus defined in the feature table
     * 
     * @return void
     */
    public function theFollowingMenusExist(TableNode $menus): void
    {
        foreach ($menus as $menuData) {
            $menuDto = $this->serializer->deserialize(
                json_encode($menuData), 
                MenuDto::class, 
                'json'
            );

            $menu = $this->menuService->create($menuDto, false);

            $this->entityManager->persist($menu);
        }

        $this->entityManager->flush();
    }
}
