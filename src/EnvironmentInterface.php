<?php

declare(strict_types=1);

namespace Dwolla;

interface EnvironmentInterface
{
    /**
     * @return string
     */
    public function getUrl(): string;
}