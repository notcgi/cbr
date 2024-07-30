<?php

declare(strict_types=1);

namespace App\Infrastructure\Queue\RateHistory;

use DateTimeImmutable;

/**
 * Symfony serializer cant handle DateTimeImmutable. Better solution is writing custom Normalizer.
 */
class RateHistoryMessage
{
    public \DateTimeImmutable $dateTime;

    public function getDateTime(): string
    {
        return $this->dateTime->format('Y-m-d');
    }

    /**
     * @throws \Exception
     */
    public function setDateTime(string|\DateTimeImmutable $dateTime): void
    {
        if (is_string($dateTime)) {
            $this->dateTime = new \DateTimeImmutable($dateTime);

            return;
        }
        $this->dateTime = $dateTime;
    }
}
