<?php

namespace MoneyProblem\Domain;

use MoneyProblem\Exception\MissingExchangeRateException;
use function array_key_exists;

class Bank
{
    private array $exchangeRates = [];

    public function __construct(array $exchangeRates = [])
    {
        $this->exchangeRates = $exchangeRates;
    }

    public static function create(Currency $from, Currency $to, float $rate): Bank
    {
        $bank = new Bank([]);
        $bank->addEchangeRate($from, $to, $rate);

        return $bank;
    }


    public function addEchangeRate(Currency $from, Currency $to, float $changeRate): void
    {
        $this->exchangeRates[($from . '->' . $to)] = $changeRate;
    }

    public function convert(Money $money, Currency $to): float
    {
        if (!$this->canConvert($money->getCurrency(), $to)) {
            throw new MissingExchangeRateException($money->getCurrency(), $to);
        }

        return $money->getCurrency() == $to
            ? $money->getAmount()
            : (new Money($money->getAmount() * $this->exchangeRates[($money->getCurrency() . '->' . $to)], $to))->getAmount();
    }

    private function canConvert(Currency $from, Currency $to): bool
    {
        return $from == $to || array_key_exists($from . '->' . $to, $this->exchangeRates);
    }
}