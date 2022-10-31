<?php

declare(strict_types=1);

namespace Dwolla;

/**
 * @template T of object
 */
final class Response
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
     * @param T $body
     * @param string[][] $headers
     * @param int $status
     */
    public function __construct($body, array $headers, int $status)
    {
        $this->body = $body;
        $this->headers = $headers;
        $this->status = $status;
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