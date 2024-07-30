<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

class RateRequest
{
    public function __construct(
        public string $date,
        public string $currencyCode,
        public string $baseCurrencyCode,
    ) {
    }
}
