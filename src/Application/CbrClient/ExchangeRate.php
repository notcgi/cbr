<?php

namespace App\Application\CbrClient;

use Symfony\Component\Serializer\Attribute\SerializedName;

class ExchangeRate
{
    #[SerializedName('CharCode')]
    public string $code;
    #[SerializedName('Value')]
    public float $value;
    public function __construct(
        string $code,
        string $value,
    ) {
        $value = str_replace(',', '.', $value);
        $this->value = (float) $value;
        $this->code = $code;
    }
}