<?php

namespace App\Application\CbrClient;

use Symfony\Component\Serializer\Attribute\SerializedName;

class ExchangeRateList
{
    /**
     * @param ExchangeRate[] $rates
     */
    public function __construct(
        #[SerializedName('Valute')]
        public array $rates,
    ) {}
}