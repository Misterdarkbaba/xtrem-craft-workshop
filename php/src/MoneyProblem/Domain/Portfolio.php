<?php

namespace MoneyProblem\Domain;

class Portfolio
{
    private array $monies = [];

    public function add(int $value, Currency $currency)
    {
        if (!key_exists($currency->getValue(), $this->monies)) {
            $this->monies[$currency->getValue()] = 0;
        }
        $this->monies[$currency->getValue()] += $value;
    }

    public function evaluate(Currency $currency, Bank $bank): float
    {
        $total = 0;
        foreach ($this->monies as $from => $mount) {
            $total += $bank->convert(new Money($mount, Currency::from($from)), $currency);
        }
        return $total;
    }
}