<?php

declare(strict_types=1);

namespace App\Infrastructure\CbrClient;

use App\Application\CbrClient\ClientInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\AutowireDecorated;
use Symfony\Contracts\Cache\CacheInterface;

#[AsDecorator(decorates: Client::class)]
class CachedClient implements ClientInterface
{
    private const CACHE_PREFIX = 'cbr-client-';

    public function __construct(
        #[AutowireDecorated]
        private ClientInterface $inner,
        private CacheInterface $cache
    ) {
    }

    public function getRates(\DateTimeImmutable $dateTime): array
    {
        return $this->cache->get(
            key: self::CACHE_PREFIX.$dateTime->format('Ymd'),
            callback: fn () => $this->inner->getRates($dateTime)
        );
    }
}
