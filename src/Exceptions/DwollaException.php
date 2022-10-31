<?php

declare(strict_types=1);

namespace Dwolla\Exceptions;

class DwollaException extends \Exception
{
    /**
     * @param string $message
     */
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}