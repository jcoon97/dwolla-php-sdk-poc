<?php

declare(strict_types=1);

namespace Dwolla;

use Dwolla\Http\GuzzleOptions;
use Dwolla\Http\HttpMethod;
use Dwolla\Models\TokenResponse;
use GuzzleHttp\Exception\GuzzleException;

final class TokenManager
{
    // Constants
    private const EXPIRES_IN_DELTA = 60;

    /**
     * @var Client
     */
    private Client $client;

    /**
     * @var int|null
     */
    private ?int $expiresIn;

    /**
     * @var TokenResponse|null
     */
    private ?TokenResponse $instance;

    /**
     * @var int
     */
    private int $updatedAt;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->updateToken();
    }

    /**
     * @return TokenResponse
     * @throws GuzzleException
     */
    public function getToken(): TokenResponse
    {
        if (!$this->isTokenFresh()) {
            return $this->updateToken();
        }
        return $this->instance;
    }

    /**
     * @return bool
     */
    private function isTokenFresh(): bool
    {
        return (
            $this->expiresIn === null
            || $this->instance === null
            || $this->expiresIn + $this->updatedAt > time() + self::EXPIRES_IN_DELTA
        );
    }

    /**
     * @return TokenResponse
     * @throws GuzzleException
     */
    private function updateToken(): TokenResponse
    {
        $guzzleOptions = GuzzleOptions::new()
            ->withFormParams([
                "client_id" => $this->client->getClientId(),
                "client_secret" => $this->client->getClientSecret(),
                "grant_type" => "client_credentials"
            ]);
        $response = $this->client->getHttpExecutor()->execute(
            new TokenResponse(),
            HttpMethod::POST,
            "token",
            $guzzleOptions
        );
        $token = $response->getBody();
        $this->expiresIn = $token->expiresIn;
        $this->instance = $token;
        $this->updatedAt = time();
        return $this->instance;
    }
}