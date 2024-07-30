<?php

declare(strict_types=1);

namespace App\Infrastructure\Queue\RateHistory;

use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class RateHistoryProducer
{
    public function __construct(
        private readonly ProducerInterface $ratesHistoryProducer,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function __invoke(RateHistoryMessage $message): void
    {
        $msgBody = $this->serializer->serialize($message, 'json');
        $this->ratesHistoryProducer->publish($msgBody);
    }
}
