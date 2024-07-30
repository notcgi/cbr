<?php

declare(strict_types=1);

namespace App\Application\CbrClient;

interface ClientInterface
{
    /**
     * @return ExchangeRate[]
     */
    public function getRates(\DateTimeImmutable $dateTime): array;
}
