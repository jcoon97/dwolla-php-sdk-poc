<?php

declare(strict_types=1);

namespace Dwolla\Models;

class HalResource
{
    /**
     * @var array<string, Link>
     */
    public array $links;

    /**
     * @param string $key
     * @return bool
     */
    public function hasLink(string $key): bool
    {
        return array_key_exists($key, $this->links);
    }

    /**
     * @param string $name
     * @return string|null
     */
    public function getHref(string $name): ?string
    {
        return $this->links[$name]->href ?? null;
    }
}