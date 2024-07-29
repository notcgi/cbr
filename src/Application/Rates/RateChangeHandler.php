<?php

namespace App\Application\Rates;

use App\Application\CbrClient\ClientInterface;
use App\Application\CbrClient\ExchangeRate;
use DateInterval;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;

class RateChangeHandler
{
    public function __construct(
        private readonly CrossRatesHandler $crossRatesHandler
    ) {}

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
            date: $date->sub(new DateInterval('P1D')),
            currencyCode: $currencyCode,
            baseCurrencyCode: $baseCurrencyCode
        );

        return $currentRate / $previousRate - 1;
    }

}