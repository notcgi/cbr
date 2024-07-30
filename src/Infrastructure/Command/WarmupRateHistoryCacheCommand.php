<?php

declare(strict_types=1);

namespace App\Infrastructure\Command;

use App\Infrastructure\Queue\RateHistory\RateHistoryMessage;
use App\Infrastructure\Queue\RateHistory\RateHistoryProducer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('warmup_rate_history_cache')]
class WarmupRateHistoryCacheCommand extends Command
{
    public function __construct(
        private readonly RateHistoryProducer $producer,
        ?string $name = null,
    ) {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Warmup rate history cache for 180 days');
        $currentDate = new \DateTime();

        $endDate = (new \DateTimeImmutable())->modify('-180 days');

        while ($currentDate >= $endDate) {
            $msg = new RateHistoryMessage();
            $msg->setDateTime(\DateTimeImmutable::createFromMutable($currentDate));
            ($this->producer)($msg);

            $currentDate->modify('-1 day');
        }
        $output->writeln('Done');

        return 0;
    }
}
