<?php

namespace Tests\MoneyProblem;

use MoneyProblem\Domain\Bank;
use MoneyProblem\Domain\Currency;

class BankBuilder
{

    private Currency $currency;
    private array $exchangeRates = [];

    public function __construct(array $exchangeRates = [])
    {
        $this->currency = Currency::EUR();
        $this->exchangeRates = $exchangeRates;
    }

    public static function aBank(): self
    {
        return new BankBuilder();
    }

    public function withPivotCurrency(Currency $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    public function withExchangeRate(float $rate, Currency $to): self
    {
        $this->exchangeRates[$to->getValue()] = $rate;
        return $this;
    }

    public function build(): Bank
    {
        $bank = Bank::create($this->currency, $this->currency, 1.0, $this->currency);

        foreach ($this->exchangeRates as $to => $rate) {
            $bank = $bank->addExchangeRate($this->currency, Currency::from($to), $rate);
        }
        return $bank;
    }
}