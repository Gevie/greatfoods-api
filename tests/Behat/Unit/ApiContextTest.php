<?php

declare(strict_types=1);

namespace App\Tests\Behat\Unit;

use App\Tests\Behat\ApiContext;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class ApiContextTest
 * 
 * Tests the abstract ApiContext class for Behat testing.
 * 
 * @package App\Tests\Behat\Unit
 * @author Stephen Speakman <hellospeakman@gmail.com>
 * 
 * @group behat
 * @group behat_api
 * @group context
 */
class ApiContextTest extends KernelTestCase
{
    /**
     * The http client.
     *
     * @var KernelBrowser|MockObject|null
     */
    private KernelBrowser|MockObject|null $client;

    /**
     * The ApiContext.
     *
     * @var ApiContext|MockObject|null
     */
    private ApiContext|MockObject|null $context;

    /**
     * The kernel.
     *
     * @var KernelInterface|MockObject|null
     */
    private KernelInterface|MockObject|null $kernelMock;

    /**
     * Executed before each test case is run.
     *
     * @return void
     */
    protected function setUp(): void
    {
        $this->client = $this->createMock(KernelBrowser::class);
        $this->kernelMock = $this->createMock(KernelInterface::class);
        $this->context = $this->getMockForAbstractClass(ApiContext::class, [
            $this->client,
            $this->kernelMock
        ]);
    }

    /**
     * Executed after each test case is run.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        $this->client = null;
        $this->kernelMock = null;

        parent::tearDown();
    }

    /**
     * Tests the "iSendARequestTo" method.
     *
     * @return void
     */
    public function testISendARequestTo(): void
    {
        // Arrange
        $this->client->expects($this->once())
            ->method('followRedirects');

        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->once())
            ->method('getParameter')
            ->with('api_base_url')
            ->willReturn('api/v1');

        $this->kernelMock->expects($this->once())
            ->method('getContainer')
            ->willReturn($container);

        $this->client->expects($this->once())
            ->method('request')
            ->with('GET', 'api/v1/users');

        $response = $this->createMock(Response::class);

        $this->client->expects($this->once())
            ->method('getResponse')
            ->willReturn($response);

        $responseData = [
            ['id' => 1, 'name' => 'Starters', 'description' => 'This is a test'],
            ['id' => 2, 'name' => 'Mains', 'description' => 'This is a test 2'],
        ];

        $response->expects($this->once())
            ->method('getContent')
            ->willReturn(json_encode($responseData));

        // Act
        $this->context->iSendARequestTo('GET', 'users');

        // Assert
        $this->assertSame($responseData, $this->context->getDecodedResponse());
    }
}
