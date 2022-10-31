<?php

declare(strict_types=1);

namespace Dwolla\Models\Customer;

final class CustomerType
{
    public const BUSINESS = "business";

    public const BUSINESS_NO_BALANCE = "business-no-balance";

    public const PERSONAL = "personal";

    public const PERSONAL_NO_BALANCE = "personal-no-balance";

    public const RECEIVE_ONLY = "receive-only";

    public const UNVERIFIED = "unverified";
}