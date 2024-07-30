<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Application\Rates\CrossRatesHandler;
use App\Application\Rates\RateChangeHandler;
use App\Application\Rates\RateNotFound;
use App\Infrastructure\CbrClient\ClientException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
class RateController extends AbstractController
{
    public function __construct(
        private readonly CrossRatesHandler $crossRatesHandler,
        private readonly RateChangeHandler $rateChangeHandler,
    ) {
    }

    #[Route('/rate')]
    public function rate(
        #[MapQueryParameter] string $date,
        #[MapQueryParameter(name: 'currency_code')] string $currencyCode,
        #[MapQueryParameter(name: 'base_currency_code')] string $baseCurrencyCode = 'RUR',
    ): JsonResponse {
        // todo: builtin validation
        try {
            $dateObject = new \DateTimeImmutable($date);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Date should be "Y-m-d" format'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

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
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (ClientException) {
            return new JsonResponse(['error' => 'External server problem'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse([
            'rate' => $crossRate,
            'rate_change' => $rateChange,
        ]);
    }
}
