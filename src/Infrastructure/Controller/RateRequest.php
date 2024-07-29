<?php

namespace App\Infrastructure\Controller;

use Symfony\Component\HttpFoundation\Request;

class RateRequest
{
    public function __construct(
        public string $date,
        public string $currencyCode,
        public string $baseCurrencyCode,
    )
    {
    }
}