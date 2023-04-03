<?php

declare(strict_types=1);

namespace App\Behat\Context;

use Behat\Behat\Context\Context;
use PHPUnit\Framework\Assert;
use RuntimeException;
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
     * The HTTP response
     *
     * @var Response
     */
    private Response $response;

    /**
     * The decoded HTTP response 
     *
     * @var array
     */
    private array $decodedResponse = [];

    /**
     * ApiContext constructor.
     *
     * @param HttpClientInterface $httpClient The HTTP client
     * @param KernelInterface $kernel The Kernel
     * @param Response $response The HTTP response (populated by iSendARequestTo())
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
        $contentType = $response->getHeaders()['content-type'][0] ?? null;
        if (strpos($contentType, 'application/json') === false) {
            return [];
        }

        $decodedResponse = json_decode($response->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Failed to decode JSON response.');
        }

        return $decodedResponse;
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
            $$response->getContent(),
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
     * Assert that the response item at index equals expected value.
     * 
     * @Then response.:index.:key equals :value
     *
     * @param integer $index The index of the array item
     * @param string $key The key to check
     * @param int|string $value The value to compare
     * 
     * @return void
     */
    public function theResponseItemAtEquals(int $index, string $key, int|string $value): void
    {
        Assert::assertEquals($value, $this->decodedResponse[$index][$key]);
    }

    /**
     * Assert that the response item at index is null.
     * 
     * @Then response.:index.:key is null
     *
     * @param integer $index The index of the array item
     * @param string $key The key to check
     * 
     * @return void
     */
    public function theResponseItemAtIsNull(int $index, string $key): void
    {
        Assert::assertEquals(null, $this->decodedResponse[$index][$key]);
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
    public function theResponseStatusCouldShouldBe(int $statusCode): void
    {
        Assert::assertSame($statusCode, $this->response->getStatusCode());
    }
}
