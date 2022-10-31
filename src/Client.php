<?php

declare(strict_types=1);

namespace Dwolla;

use Dwolla\Exceptions\DwollaException;
use Dwolla\Exceptions\ResponseException;
use Dwolla\Http\GuzzleOptions;
use Dwolla\Http\HttpExecutor;
use Dwolla\Http\HttpMethod;
use GuzzleHttp\Exception\GuzzleException;

final class Client
{
    /**
     * @var string
     */
    private string $clientId;

    /**
     * @var string
     */
    private string $clientSecret;

    /**
     * @var HttpExecutor
     */
    private HttpExecutor $httpExecutor;

    /**
     * @var TokenManager
     */
    private TokenManager $tokenManager;

    /**
     * @param string $clientId
     * @param string $clientSecret
     * @param EnvironmentInterface|null $environment
     */
    public function __construct(string $clientId, string $clientSecret, ?EnvironmentInterface $environment = null)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;

        $this->httpExecutor = new HttpExecutor($environment !== null ? $environment : Environment::withDwolla());
        $this->tokenManager = new TokenManager($this);
    }

    /**
     * @template T of object
     * @param string $url
     * @param string[]|null $query
     * @param string[]|null $headers
     * @param T|null $deserializeAs
     * @return Response<T>
     * @throws GuzzleException|ResponseException
     */
    public function delete(string $url, ?array $query = null, ?array $headers = null, $deserializeAs = null): Response
    {
        $token = $this->tokenManager->getToken();
        $guzzleOptions = GuzzleOptions::new()
            ->withHeaders($token->getHeaders($headers))
            ->withQuery($query);
        $response = $this->httpExecutor->execute($deserializeAs, HttpMethod::DELETE, $url, $guzzleOptions);

        if ($response->getStatus() >= 400) {
            throw new ResponseException($response);
        }
        return $response;
    }

    /**
     * @template T of object
     * @param Response $response
     * @param T|null $deserializeAs
     * @return Response<T>
     * @throws DwollaException|GuzzleException
     */
    private function follow(Response $response, $deserializeAs): Response
    {
        $location = $response->getHeaders()["Location"][0];

        if ($location === null) {
            throw new DwollaException("Cannot follow URL, Location header returned null");
        }
        return $this->get($location, null, null, $deserializeAs);
    }

    /**
     * @template T of object
     * @param string $url
     * @param string[]|null $query
     * @param string[]|null $headers
     * @param T|null $deserializeAs
     * @return Response<T>
     * @throws GuzzleException
     * @throws ResponseException<T>
     */
    public function get(string $url, ?array $query = null, ?array $headers = null, $deserializeAs = null): Response
    {
        $token = $this->tokenManager->getToken();
        $guzzleOptions = GuzzleOptions::new()
            ->withHeaders($token->getHeaders($headers))
            ->withQuery($query);
        $response = $this->httpExecutor->execute($deserializeAs, HttpMethod::GET, $url, $guzzleOptions);

        if ($response->getStatus() >= 400) {
            throw new ResponseException($response);
        }
        return $response;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    /**
     * @return HttpExecutor
     */
    public function getHttpExecutor(): HttpExecutor
    {
        return $this->httpExecutor;
    }

    /**
     * @template TBody of JsonSerializable
     * @template TResponse of object
     * @param string $url
     * @param array|TBody|null $body
     * @param string[]|null $headers
     * @param TResponse|null $deserializeAs
     * @return Response<TResponse>
     * @throws GuzzleException|ResponseException
     */
    public function post(string $url, $body, array $headers = null, $deserializeAs = null): Response
    {
        $token = $this->tokenManager->getToken();
        $guzzleOptions = GuzzleOptions::new()
            ->withHeaders($token->getHeaders($headers))
            ->withJson($body ?? []);
        $response = $this->httpExecutor->execute($deserializeAs, HttpMethod::POST, $url, $guzzleOptions);

        if ($response->getStatus() >= 400) {
            throw new ResponseException($response);
        }
        return $response;
    }

    /**
     * @template TBody of JsonSerializable
     * @template TResponse of object
     * @param string $url
     * @param array|TBody|null $body
     * @param string[]|null $headers
     * @param TResponse|null $deserializeAs
     * @return Response<TResponse>
     * @throws DwollaException|GuzzleException|ResponseException
     */
    public function postFollow(string $url, $body, array $headers = null, $deserializeAs = null): Response
    {
        return $this->follow($this->post($url, $body, $headers), $deserializeAs);
    }
}