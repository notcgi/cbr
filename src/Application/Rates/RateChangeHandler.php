<?php

declare(strict_types=1);

namespace App\Application\Rates;

class RateChangeHandler
{
    public function __construct(
        private readonly CrossRatesHandler $crossRatesHandler
    ) {
    }

    /**
     * @throws RateNotFound
     */
    public function __invoke(
        \DateTimeImmutable $date,
        string $currencyCode,
        string $baseCurrencyCode,
    ): float {
        $currentRate = ($this->crossRatesHandler)(
            date: $date,
            currencyCode: $currencyCode,
            baseCurrencyCode: $baseCurrencyCode
        );
        $previousRate = ($this->crossRatesHandler)(
            date: $date->sub(new \DateInterval('P1D')),
            currencyCode: $currencyCode,
            baseCurrencyCode: $baseCurrencyCode
        );

        return $currentRate / $previousRate - 1;
    }
}
