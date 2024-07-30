<?php

declare(strict_types=1);

namespace App\Infrastructure\Queue\RateHistory;

use App\Application\CbrClient\ClientInterface;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\SerializerInterface;

#[AsAlias('rates_history_consumer_service')]
class RateHistoryConsumer implements ConsumerInterface
{
    public function __construct(
        #[Autowire(service: 'cbrClient.cached')]
        private readonly ClientInterface $client,
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function execute(AMQPMessage $msg): int
    {
        /** @var RateHistoryMessage $rateHistoryMessage */
        $rateHistoryMessage = $this->serializer->deserialize(
            data: $msg->getBody(),
            type: RateHistoryMessage::class,
            format: 'json'
        );

        $this->client->getRates($rateHistoryMessage->dateTime);

        return ConsumerInterface::MSG_ACK;
    }
}
