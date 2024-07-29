<?php

namespace App\Application\CbrClient;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

interface ClientInterface
{
    /**
     * @param \DateTimeImmutable $dateTime
     * @return ExchangeRate[]
     */
    public function getRates(\DateTimeImmutable $dateTime): array;
}