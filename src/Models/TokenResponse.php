<?php

declare(strict_types=1);

namespace Dwolla\Models;

final class TokenResponse
{
    /**
     * @var string
     */
    public string $accessToken;

    /**
     * @var int
     */
    public int $expiresIn;

    /**
     * @var string
     */
    public string $tokenType;

    /**
     * @param string[]|null $additionalHeaders
     * @return string[]
     */
    public function getHeaders(?array $additionalHeaders): array
    {
        return array_merge(
            $additionalHeaders ?: [],
            [
                "Accept" => "application/vnd.dwolla.v1.hal+json",
                "Authorization" => implode(" ", ["Bearer", $this->accessToken])
            ]
        );
    }
}