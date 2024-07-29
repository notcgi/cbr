<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\CbrClient\ClientInterface;
use App\Application\CbrClient\ExchangeRate;
use App\Application\Rates\CrossRatesHandler;
use App\Application\Rates\RateChangeHandler;
use App\Application\Rates\RateNotFound;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class RateController extends AbstractController
{
    public function __construct(
        private readonly CrossRatesHandler $crossRatesHandler,
        private readonly RateChangeHandler $rateChangeHandler,
    )
    {
    }

    #[Route('/rate')]
    public function rate(
        #[MapQueryParameter] string $date,
        #[MapQueryParameter(name: 'currency_code')] string $currencyCode,
        #[MapQueryParameter(name: 'base_currency_code')] string $baseCurrencyCode = 'RUR',
    ): JsonResponse
    {
        $dateObject = new \DateTimeImmutable($date);
        try {
            $crossRate = ($this->crossRatesHandler)(
                date: $dateObject,
                currencyCode: $currencyCode,
                baseCurrencyCode: $baseCurrencyCode
            );
            $rateChange = ($this->rateChangeHandler)(
                date: $dateObject,
                currencyCode: $currencyCode,
                baseCurrencyCode: $baseCurrencyCode
            );
        } catch (RateNotFound $e) {
            return new JsonResponse(['error' => $e->getMessage()],422);
        }
        return new JsonResponse([
            'rate' => $crossRate,
            'rate_change' => $rateChange
        ]);
    }
}
