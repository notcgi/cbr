<?php

declare(strict_types=1);

namespace App\Application\Rates;

class RateNotFound extends \Exception
{
    public function __construct(string $code)
    {
        parent::__construct("Rate for $code not found");
    }
}
