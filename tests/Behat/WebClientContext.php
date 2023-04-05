<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use Behat\Behat\Event\ScenarioEvent;
use LogicException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * Class WebClientContext
 * 
 * Inspired by Symfony\Bundle\FrameworkBundle\Test\WebClientContext
 *  Ensure that the responses of HTTP requests are coming from the test environment.
 * 
 * @package App\Tests\Behat
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
abstract class WebClientContext extends KernelContext
{
    /**
     * Creates a KernelBrowser.
     *
     * @param array $options An array of options to pass to the createKernel method
     * @param array $server An array of server parameters
     * 
     * @return KernelBrowser The client
     */
    protected static function createClient(array $options = [], array $server = []): KernelBrowser
    {
        if (static::$booted) {
            throw new LogicException(sprintf(
                'Booting the kernel before calling "%s()" is not supported, the kernel should only be booted once.',
                __METHOD__
            ));
        }

        $kernel = static::bootKernel($options);

        try {
            $client = $kernel->getContainer()->get('test.client');
        } catch (ServiceNotFoundException) {
            if (class_exists(KernelBrowser::class)) {
                throw new LogicException(
                    'You cannot create the client if the "framework.test" config is not set to true.'
                );
            }

            throw new LogicException(
                'You cannot create the client if the BrowserKit component is not available'
            );
        }

        $client->setServerParameters($server);

        return self::getClient($client);
    }

    /**
     * Gets an instance of the client.
     *
     * @param AbstractBrowser|null $newClient The new client
     * 
     * @return AbstractBrowser|null The client
     */
    private static function getClient(AbstractBrowser $newClient = null): ?AbstractBrowser
    {
        static $client;

        if (0 < \func_num_args()) {
            return $client = $newClient;
        }

        if (!$client instanceof AbstractBrowser) {
            throw new LogicException(sprintf(
                'A client must be set to make assertions on it. Did you forget to call "%s::createClient()"?', 
                __CLASS__
            ));
        }

        return $client;
    }

    /**
     * Runs after any given behat scenario to reset the kernel and client.
     * 
     * @AfterScenario
     *
     * @return void
     */
    protected function tearDown(): void
    {
        parent::tearDown();
        self::getClient(null);
    }
}
