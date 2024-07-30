<?php

declare(strict_types=1);

namespace App\Infrastructure\CbrClient;

use App\Application\CbrClient\ClientInterface;
use App\Application\CbrClient\ExchangeRateList;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Client implements ClientInterface
{
    public const CBR_DAILY_URL = 'http://www.cbr.ru/scripts/XML_daily.asp';

    /**
     * @param XmlSerializer $serializer
     */
    public function __construct(
        #[Autowire(service: XmlSerializer::class)]
        private SerializerInterface $serializer,
        private HttpClientInterface $httpClient,
    ) {
    }

    public function getRates(\DateTimeImmutable $dateTime): array
    {
        $url = self::CBR_DAILY_URL.'?'.http_build_query(
            ['date_req' => $dateTime->format('d/m/Y')]
        );

        try {
            $response = $this->httpClient->request('GET', $url);
            $content = $response->getContent();

            return $this->serializer->deserialize($content, ExchangeRateList::class, 'xml')->rates;
        } catch (\Throwable $e) {
            throw $e; // todo
        }
    }
}
