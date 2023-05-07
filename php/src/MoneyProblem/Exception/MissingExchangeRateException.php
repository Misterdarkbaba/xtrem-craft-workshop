<?php

namespace MoneyProblem\Exception;

use MoneyProblem\Domain\Currency;

class MissingExchangeRateException extends \Exception
{
    public function __construct(Currency $currency1, Currency $currency2)
    {
        parent::__construct(sprintf('The system has no exchange Rate defined for %s->%s', $currency1, $currency2));

    }
}