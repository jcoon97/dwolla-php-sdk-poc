<?php

declare(strict_types=1);

namespace Dwolla\Models\Customer;

final class CreateReceiveOnlyCustomerRequest extends AbstractCreateCustomerRequest
{
    public function __construct()
    {
        parent::__construct("receive-only");
    }
}