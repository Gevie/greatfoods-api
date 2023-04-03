<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use InvalidArgumentException;
use PHPUnit\Framework\Assert;
use RuntimeException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Class ApiContext
 * 
 * Provides API related functionality for API related context classes.
 * 
 * @package App\Behat\Context
 * @author Stephen Speakman <hellospeakman@gmail.com>
 */
abstract class ApiContext implements Context
{
    private const ALLOWED_HTTP_METHODS = [
        'DELETE',
        'GET',
        'HEAD',
        'PATCH',
        'POST',
        'PUT',
    ];

    /**
     * The decoded HTTP response 
     *
     * @var array
     */
    private array $decodedResponse = [];

    /**
     * The HTTP response
     *
     * @var Response
     */
    private Response $response;

    /**
     * ApiContext constructor.
     *
     * @param HttpClientInterface $httpClient The HTTP client
     * @param KernelInterface $kernel The Kernel
     */
    public function __construct(
        private HttpClientInterface $httpClient,
        private KernelInterface $kernel
    ) {
        // ...
    }

    /**
     * Pretty-prints the JSON response for debugging.
     * 
     * @Then debug response
     *
     * @return void
     */
    public function debugResponse(): void
    {
        VarDumper::dump($this->decodedResponse);
    }

    /**
     * Decodes the response content into an array if possible.
     *
     * @param ResponseInterface $response The HTTP response
     * 
     * @return array The decoded response or an empty array
     */
    private function decodeJsonResponse(ResponseInterface $response): array
    {
        $decodedResponse = json_decode($response->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Failed to decode JSON response.');
        }

        return $decodedResponse;
    }

    /**
     * Walks through the response path to get the appropriate key value.
     *
     * @param string $path The path to obtain (i.e. 0.products.0.name)
     * 
     * @return mixed The array value at the path
     * 
     * @throws InvalidArgumentException If the path specified is invalid
     */
    private function getPath(string $path): mixed
    {
        $pathParts = explode('.', $path);
        $currentPath = $this->decodedResponse;

        foreach ($pathParts as $part) {
            if (!isset($currentPath[$part])) {
                throw new InvalidArgumentException(sprintf(
                    'The path "%s" does not exist in the response',
                    $path
                ));
            }

            $currentPath = $currentPath[$part];
        }

        return $currentPath;
    }

    /**
     * Handle the sending of requests and store the response property.
     * 
     * @When I send a :method request to :url
     *
     * @param string $method The HTTP request method
     * @param string $url The URL to request
     * 
     * @return void
     */
    public function iSendARequestTo(string $method, string $endpoint): void
    {
        if (! in_array($method, self::ALLOWED_HTTP_METHODS)) {
            throw new RuntimeException(sprintf('The method "%s" is not a valid HTTP method', $method));
        }

        $response = $this->httpClient->request($method, sprintf('%s/%s',
            $this->kernel->getContainer()->getParameter('api_base_url'),
            $endpoint
        ));

        $this->decodedResponse = $this->decodeJsonResponse($response);
        $this->response = new Response(
            $response->getContent(),
            $response->getStatusCode(),
            $response->getHeaders()
        );
    }

    /**
     * Assert that the response contains a specific number of items.
     * 
     * @Then the response contains :count items
     *
     * @param integer $count The number of items to assert
     * 
     * @return void
     */
    public function theResponseContainsItems(int $count): void
    {
        Assert::assertCount($count, $this->decodedResponse);
    }

    /**
     * Assert that the response item at path contains a specific number of items.
     *
     * @Then response.:path contains :count items
     * 
     * @param string $path The path to check
     * @param integer $count The number of items to assert
     * 
     * @return void
     */
    public function theResponseItemContainsItems(string $path, int $count): void
    {
        $responsePath = $this->getPath($path);
        Assert::assertCount($count, $responsePath);
    }

    /**
     * Assert that the response item at path equals expected value.
     * 
     * @Then response.:path equals :value
     *
     * @param string $path The path to check
     * @param mixed $value The value to compare
     * 
     * @return void
     */
    public function theResponseItemAtEquals(string $path, mixed $value): void
    {
        $responsePath = $this->getPath($path);
        Assert::assertEquals($value, $responsePath);
    }

    /**
     * Assert that the response item at index is null.
     * 
     * @Then response.:path is null
     *
     * @param string $path The path to check
     * @param string $key The key to check
     * 
     * @return void
     */
    public function theResponseItemAtIsNull(string $path): void
    {
        $responsePath = $this->getPath($path);
        Assert::assertEquals(null, $responsePath);
    }

    /**
     * Check the response code matches the expected response code.
     * 
     * @Then the response status code should be :statusCode
     *
     * @param integer $statusCode The status code to expect
     * 
     * @return void
     */
    public function theResponseStatusCodeShouldBe(int $statusCode): void
    {
        Assert::assertSame($statusCode, $this->response->getStatusCode());
    }
}
