<?php

declare(strict_types=1);

namespace App\Application\CbrClient;

use App\Infrastructure\CbrClient\ClientException;

interface ClientInterface
{
    /**
     * @return ExchangeRate[]
     *
     * @throws ClientException
     */
    public function getRates(\DateTimeImmutable $dateTime): array;
}
