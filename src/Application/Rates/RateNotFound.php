<?php

namespace App\Application\Rates;

class RateNotFound extends \Exception
{
    public function __construct(string $code)
    {
        parent::__construct("Rate for $code not found");
    }
}