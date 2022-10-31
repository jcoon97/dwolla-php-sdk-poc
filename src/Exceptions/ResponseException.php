<?php

declare(strict_types=1);

namespace Dwolla\Exceptions;

use Dwolla\Response;

/**
 * @template T of object
 */
final class ResponseException extends DwollaException
{
    /**
     * @var T
     */
    private $body;

    /**
     * @var string[][]
     */
    private array $headers;

    /**
     * @var int
     */
    private int $status;

    /**
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        parent::__construct("An error was returned from Dwolla: " . json_encode($response->getBody()));

        $this->body = $response->getBody();
        $this->headers = $response->getHeaders();
        $this->status = $response->getStatus();
    }

    /**
     * @return T
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return string[][]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }
}