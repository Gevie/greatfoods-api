<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Behat\Event\ScenarioEvent;
use LogicException;
use RuntimeException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\Service\ResetInterface;

/**
 * Class KernelContext
 * 
 * Inspired by Symfony\Bundle\FrameworkBundle\Test\KernelContext
 *  Ensure that the responses of HTTP requests are coming from the test environment.
 * 
 * @package App\Tests\Behat
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
abstract class KernelContext implements Context
{
    /**
     * Whether the kernel is booted or not.
     *
     * @var boolean
     */
    protected static bool $booted = false;

    /**
     * The Kernel class name.
     *
     * @var string
     */
    protected static string $class;

    /**
     * The Kernel.
     *
     * @var object
     */
    protected static $kernel;

    /**
     * Boots the Kernel for this test.
     * 
     * @param array $options An array of options to pass to the createKernel method.
     * 
     * @return KernelInterface The kernel
     */
    protected static function bootKernel(array $options = []): KernelInterface
    {
        static::ensureKernelShutdown();

        $kernel = static::createKernel($options);
        $kernel->boot();
        static::$kernel = $kernel;
        static::$booted = true;

        return static::$kernel;
    }

    /**
     * Creates a Kernel.
     *
     * Available options:
     *
     *  * environment
     *  * debug
     * 
     * @param array $options An array of options used to create the kernel
     * 
     * @return KernelInterface The kernel
     */
    protected static function createKernel(array $options = []): KernelInterface
    {
        static::$class ??= static::getKernelClass();

        if (isset($options['environment'])) {
            $env = $options['environment'];
        } else if (isset($_ENV['APP_ENV'])) {
            $env = $_ENV['APP_ENV'];
        } else if (isset($_SERVER['APP_ENV'])) {
            $env = $_SERVER['APP_ENV'];
        } else {
            $env = 'test';
        }

        if (isset($options['debug'])) {
            $debug = $options['debug'];
        } else if (isset($_ENV['APP_DEBUG'])) {
            $debug = $_ENV['APP_DEBUG'];
        } else if (isset($_SERVER['APP_DEBUG'])) {
            $debug = $_SERVER['APP_DEBUG'];
        } else {
            $debug = true;
        }

        return new static::$class($env, (bool) $debug);
    }

    /**
     * Shuts the kernel down if it was used in the test - called by the tearDown method by default.
     * 
     * @return void
     */
    protected static function ensureKernelShutdown(): void
    {
        if (null === static::$kernel) {
            return;
        }

        static::$kernel->boot();
        $container = static::$kernel->getContainer();
        static::$kernel->shutdown();
        static::$booted = false;

        if ($container instanceof ResetInterface) {
            $container->reset();
        }
    }

    /**
     * Provides a dedicated test container with access to both public and private
     * services. The container will not include private services that have been
     * inlined or removed. Private services will be removed when they are not
     * used by other services.
     *
     * Using this method is the best way to get a container from your test code.
     *
     * @return ContainerInterface The container
     * 
     * @throws LogicException If the test.service_container can not be found
     */
    protected static function getContainer(): ContainerInterface
    {
        if (!static::$booted) {
            static::bootKernel();
        }

        try {
            return self::$kernel->getContainer()->get('test.service_container');
        } catch (ServiceNotFoundException $e) {
            throw new LogicException('Could not find service "test.service_container". Try updating the "framework.test" config to "true".', 0, $e);
        }
    }

    /**
     * Gets the Kernel class name.
     * 
     * @return string The kernel class name
     * 
     * @throws RuntimeException If the kernel class does not exist or cannot be autoloaded
     * @throws LogicException If the kernel class variable is not set
     */
    protected static function getKernelClass(): string
    {
        if (!isset($_SERVER['KERNEL_CLASS']) && !isset($_ENV['KERNEL_CLASS'])) {
            throw new LogicException(sprintf('You must set the KERNEL_CLASS environment variable to the fully-qualified class name of your Kernel in phpunit.xml / phpunit.xml.dist or override the "%1$s::createKernel()" or "%1$s::getKernelClass()" method.', static::class));
        }

        if (!class_exists($class = $_ENV['KERNEL_CLASS'] ?? $_SERVER['KERNEL_CLASS'])) {
            throw new RuntimeException(sprintf('Class "%s" doesn\'t exist or cannot be autoloaded. Check that the KERNEL_CLASS value in phpunit.xml matches the fully-qualified class name of your Kernel or override the "%s::createKernel()" method.', $class, static::class));
        }

        return $class;
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
        static::ensureKernelShutdown();
        static::$class = null;
        static::$kernel = null;
        static::$booted = false;
    }
}
