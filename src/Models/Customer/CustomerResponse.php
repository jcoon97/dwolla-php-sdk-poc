<?php

declare(strict_types=1);

namespace Dwolla\Models\Customer;

use Dwolla\Models\HalResource;

final class CustomerResponse extends HalResource
{
    /**
     * @var string
     */
    public string $id;

    /**
     * @var string
     */
    public string $firstName;

    /**
     * @var string
     */
    public string $lastName;

    /**
     * @var string|null
     */
    public ?string $email;

    /**
     * @var string
     */
    public string $type;
}