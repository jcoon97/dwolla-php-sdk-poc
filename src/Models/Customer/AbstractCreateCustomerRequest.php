<?php

declare(strict_types=1);

namespace Dwolla\Models\Customer;

abstract class AbstractCreateCustomerRequest implements \JsonSerializable
{
    public string $firstName;

    public string $lastName;

    public string $email;

    private ?string $type;

    public ?string $businessName;

    public ?string $ipAddress;

    public ?string $correlationId;

    protected function __construct(string $type)
    {
        $this->type = $type;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}