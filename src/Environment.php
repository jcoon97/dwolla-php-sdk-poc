<?php

declare(strict_types=1);

namespace Dwolla;

final class Environment implements EnvironmentInterface
{
    private const PRODUCTION_API_URL = "https://api.dwolla.com";

    private const SANDBOX_API_URL = "https://api-sandbox.dwolla.com";

    /**
     * @var string
     */
    private string $url;

    /**
     * @param string|null $url
     * @param bool|null $isSandbox
     */
    private function __construct(?string $url = null, ?bool $isSandbox = null)
    {
        $this->url = $url ?? ($isSandbox ? self::SANDBOX_API_URL : self::PRODUCTION_API_URL);
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param bool $isSandbox
     * @return Environment
     */
    public static function withDwolla(bool $isSandbox = false): Environment
    {
        return new Environment(null, $isSandbox);
    }

    /**
     * @param string $url
     * @return Environment
     */
    public static function withCustom(string $url): Environment
    {
        return new Environment($url);
    }
}