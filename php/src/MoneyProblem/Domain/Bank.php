<?php

namespace MoneyProblem\Domain;

use MoneyProblem\Exception\MissingExchangeRateException;
use function array_key_exists;

class Bank
{
    private array $exchangeRates = [];

    private function __construct(array $exchangeRates = [])
    {
        $this->exchangeRates = $exchangeRates;
    }

    public static function create(Currency $from, Currency $to, float $rate): Bank
    {
        $bank = new Bank([]);
        return $bank->addEchangeRate($from, $to, $rate);
    }

    public function addEchangeRate(Currency $from, Currency $to, float $changeRate): Bank
    {
        $exchangeRates = array_merge($this->exchangeRates, [($from . '->' . $to) => $changeRate]);

        return new Bank($exchangeRates);
    }

    public function convert(Money $money, Currency $to): Money
    {
        if (!$this->canConvert($money->getCurrency(), $to)) {
            throw new MissingExchangeRateException($money->getCurrency(), $to);
        }

        return $money->getCurrency() == $to
            ? $money
            : (new Money($money->getAmount() * $this->exchangeRates[($money->getCurrency() . '->' . $to)], $to));
    }

    private function canConvert(Currency $from, Currency $to): bool
    {
        return $from == $to || array_key_exists($from . '->' . $to, $this->exchangeRates);
    }
}