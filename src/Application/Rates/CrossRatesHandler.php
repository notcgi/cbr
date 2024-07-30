<?php

declare(strict_types=1);

namespace App\Application\Rates;

use App\Application\CbrClient\ClientInterface;
use App\Application\CbrClient\ExchangeRate;
use App\Infrastructure\CbrClient\ClientException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class CrossRatesHandler
{
    public function __construct(
        #[Autowire(service: 'cbrClient.cached')]
        private readonly ClientInterface $client
    ) {
    }

    /**
     * @throws RateNotFound
     * @throws ClientException
     */
    public function __invoke(
        \DateTimeImmutable $date,
        string $currencyCode,
        string $baseCurrencyCode,
    ): float {
        $rates = $this->client->getRates($date);

        $targetRateValue = $this->getRateValueByCode($rates, $currencyCode);
        $baseRateValue = $this->getRateValueByCode($rates, $baseCurrencyCode);

        return $targetRateValue / $baseRateValue;
    }

    /**
     * @throws RateNotFound
     */
    private function getRateValueByCode(array $rates, string $code): float
    {
        if ('RUR' === $code || 'RUB' === $code) {
            return 1;
        }

        /** @var ExchangeRate $rate */
        $rate = array_values(array_filter($rates, static fn (ExchangeRate $rate) => $rate->code === $code))[0] ?? null;

        if (null === $rate) {
            throw new RateNotFound($code);
        }

        return $rate->value;
    }
}
