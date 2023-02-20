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


    public static function create(Currency $currency1, Currency $currency2, float $rate): Bank
    {
        $bank = new Bank([]);
        $bank->addEchangeRate($currency1, $currency2, $rate);

        return $bank;
    }


    public function addEchangeRate(Currency $from, Currency $to, float $changeRate): void
    {
        $this->exchangeRates[($from . '->' . $to)] = $changeRate;
    }

    /**
     * @throws MissingExchangeRateException
     */
    public function convert(float $amount, Currency $from, Currency $to): float
    {
        if (!$this->canConvert($from, $to)) {
            throw new MissingExchangeRateException($from, $to);
        }

        return $from == $to
            ? $amount
            : $amount * $this->exchangeRates[($from . '->' . $to)];
    }

    private function canConvert(Currency $from, Currency $to): bool
    {
        return $from == $to || array_key_exists($from . '->' . $to, $this->exchangeRates);
    }
}