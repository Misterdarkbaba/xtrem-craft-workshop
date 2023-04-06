<?php

namespace MoneyProblem\Domain;

use MoneyProblem\Exception\MissingExchangeRateException;

class Portfolio
{
    private array $monies = [];

    public function add(Money $money): void
    {
        if (!key_exists($money->getCurrency()->getValue(), $this->monies)) {
            $this->monies[$money->getCurrency()->getValue()] = 0;
        }
        $this->monies[$money->getCurrency()->getValue()] += $money->getAmount();
    }

    /**
     * @throws MissingExchangeRateException
     * @throws \Exception
     */
    public function evaluate(Currency $currency, Bank $bank): float
    {
        $total = 0;
        foreach ($this->monies as $from => $mount) {
            $total += $bank->convert(new Money($mount, Currency::from($from)), $currency)->getAmount();
        }
        return $total;
    }
}