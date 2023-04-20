<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use InvalidArgumentException;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\VarDumper\VarDumper;

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
     * @param KernelBrowser $client The Kernel Browser (HTTP Client)
     * @param KernelInterface $kernel The Kernel
     */
    public function __construct(
        private KernelBrowser $client,
        private KernelInterface $kernel
    ) {
        // ...
    }

     /**
     * Pretty-prints the JSON response for debugging.
     * 
     * @Then debug formatted response
     *
     * @return void
     */
    public function debugFormattedResponse(): void
    {
        VarDumper::dump($this->decodedResponse);
    }

    /**
     * Prints the raw JSON response for debugging.
     * 
     * @Then debug response
     *
     * @return void
     */
    public function debugResponse(): void
    {
        print($this->response->getContent());
    }

    /**
     * Decodes the response content into an array if possible.
     *
     * @param Response $response The HTTP response
     * 
     * @return array The decoded response or an empty array
     */
    private function decodeJsonResponse(Response $response): array
    {
        $decodedResponse = json_decode($response->getContent(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException(sprintf('Failed to decode JSON response, error: "%s".', json_last_error_msg()));
        }

        return $decodedResponse;
    }

    /**
     * Gets the decoded response property.
     * 
     * This is helpful for testing since this class is an abstract class.
     *
     * @return array The decoded response
     */
    public function getDecodedResponse(): array
    {
        return $this->decodedResponse;
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
     * @When I send a :method request to :url with:
     *
     * @param string $method The HTTP request method
     * @param string $url The URL to request
     * @param PyStringNode|null $data The json data to send (optional)
     * 
     * @return void
     */
    public function iSendARequestTo(string $method, string $endpoint, ?PyStringNode $data = null): void
    {
        if (! in_array($method, self::ALLOWED_HTTP_METHODS)) {
            throw new RuntimeException(sprintf('The method "%s" is not a valid HTTP method', $method));
        }

        $options = [];
        if ($data !== null) {
            $options = json_decode($data->getRaw(), true, 512, JSON_THROW_ON_ERROR);
        }

        $this->client->followRedirects();
        $this->client->request(
            $method, 
            sprintf('%s/%s', $this->kernel->getContainer()->getParameter('api_base_url'), $endpoint), 
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($options)
        );

        $this->response = $this->client->getResponse();
        $this->decodedResponse = $this->decodeJsonResponse($this->response);
    }

    /**
     * Assert that the response contains a specific number of items.
     * 
     * @Then the response contains :count items
     *
     * @param int $count The number of items to assert
     * 
     * @return void
     */
    public function theResponseContainsItems(int $count): void
    {
        assert($count === count($this->decodedResponse));
    }

    /**
     * Assert that the response does not contain a specific property.
     * 
     * @Then response does not have property :property
     *
     * @param string $property The property to check
     * 
     * @return void
     */
    public function theResponseDoesNotHaveProperty(string $property): void
    {
        assert(false === isset($this->decodedResponse[$property]));
    }

    /**
     * Assert that the response contains a specific property.
     * 
     * @Then response has property :property
     *
     * @param string $property The property to check
     * 
     * @return void
     */
    public function theResponseHasProperty(string $property): void
    {
        assert(true === isset($this->decodedResponse[$property]));
    }

    /**
     * Assert that the response item at path contains a specific number of items.
     *
     * @Then response.:path contains :count items
     * 
     * @param string $path The path to check
     * @param int $count The number of items to assert
     * 
     * @return void
     */
    public function theResponseItemContainsItems(string $path, int $count): void
    {
        $responsePath = $this->getPath($path);

        assert($count === count($responsePath));
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

        assert($value === $responsePath);
    }

    /**
     * Assert that the response item at path equals expected integer value.
     * 
     * @Then response.:path integer equals :value
     *
     * @param string $path The path to check
     * @param int $value The value to compare
     * 
     * @return void
     */
    public function theResponseItemAtEqualsInteger(string $path, int $value): void
    {
        $responsePath = $this->getPath($path);

        assert($value === $responsePath);
    }

    /**
     * Assert that the response item at path equals expected string value.
     * 
     * @Then response.:path string equals :value
     *
     * @param string $path The path to check
     * @param string $value The value to compare
     * 
     * @return void
     */
    public function theResponseItemAtEqualsString(string $path, string $value): void
    {
        $responsePath = $this->getPath($path);

        assert($value === $responsePath);
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

        assert("" === (string) $responsePath);
    }

    /**
     * Check the response code matches the expected response code.
     * 
     * @Then the response status code should be :statusCode
     *
     * @param integer $statusCode The status code to expect
     * 
     * @return bool
     */
    public function theResponseStatusCodeShouldBe(int $statusCode): void
    {
        assert($statusCode === $this->response->getStatusCode());
    }
}
