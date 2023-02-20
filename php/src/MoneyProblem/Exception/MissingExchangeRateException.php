<?php

namespace MoneyProblem\Exception;

use MoneyProblem\Domain\Currency;

class MissingExchangeRateException extends \Exception
{
    public function __construct(Currency $currency1, Currency $currency2)
    {
        parent::__construct(sprintf('%s->%s', $currency1, $currency2));

    }
}