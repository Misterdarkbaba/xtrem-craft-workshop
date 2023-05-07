<?php

namespace MoneyProblem\Domain;

use MoneyProblem\Exception\MissingExchangeRateException;
use function array_key_exists;

class Bank
{
    private array $exchangeRates;
    private Currency $pivotCurrency;

    private function __construct(array $exchangeRates, Currency $pivotCurrency)
    {
        $this->exchangeRates = $exchangeRates;
        $this->pivotCurrency = $pivotCurrency;
    }

    public static function create(Currency $from, Currency $to, float $rate, Currency $pivotCurrency): Bank
    {
        $bank = new Bank([], $pivotCurrency);
        return $bank->addExchangeRate($from, $to, $rate);
    }

    public function addExchangeRate(Currency $from, Currency $to, float $changeRate): Bank
    {
        if (array_key_exists($from . '->' . $to, $this->exchangeRates)) {
            if($this->exchangeRates[($from . '->' . $to)] === $changeRate){
                throw new \InvalidArgumentException('An Exchange Rate can not be added for the Pivot Currency');
            }
        }
        if ($changeRate <= 0) {
            throw new \InvalidArgumentException('Exchange Rate should be greater than 0');
        }

        $exchangeRates = array_merge($this->exchangeRates, [($from . '->' . $to) => $changeRate]);
        $exchangeRates = array_merge($exchangeRates, [($to . '->' . $from) => 1/$changeRate]);

        return new Bank($exchangeRates, $this->pivotCurrency);
    }

//    public function convert(Money $money, Currency $to): Money
//    {
//        if(array_key_exists($this->pivotCurrency . '->' . $money->getCurrency(), $this->exchangeRates)
//            && array_key_exists($this->pivotCurrency . '->' . $to, $this->exchangeRates)){
//            $exchangeRateFrom = $this->exchangeRates[($this->pivotCurrency . '->' . $money->getCurrency())];
//            $exchangeRateTo = $this->exchangeRates[($this->pivotCurrency . '->' . $to)];
//            $exchangeRate = $exchangeRateFrom * $exchangeRateTo;
//            (new Money($money->getAmount() * $exchangeRate, $to));
//        }
//        else {
//            if (!$this->canConvert($money->getCurrency(), $to)) {
//                throw new MissingExchangeRateException($money->getCurrency(), $to);
//            }
//        }
//
//        return $money->getCurrency() == $to
//            ? $money
//            : (new Money($money->getAmount() * $this->exchangeRates[($money->getCurrency() . '->' . $to)], $to));
//    }



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
        if ($from == $to || array_key_exists($from . '->' . $to, $this->exchangeRates)) {
            return true;
        }
//        if(array_key_exists($this->pivotCurrency->getValue() . '->' . $from->getValue(), $this->exchangeRates)
//            && array_key_exists(Currency::EUR()->getValue() . '->' . $to->getValue(), $this->exchangeRates)){
//
//            // KRW -> USD = (Taux Pivot/KRW) x (Taux Pivot/USD)
//            $changeRateFrom = $this->exchangeRates[(Currency::EUR()->getValue() . '->' . $from->getValue())];
//            $changeRateTo = $this->exchangeRates[(Currency::EUR()->getValue() . '->' . $to->getValue())];
//            $changeRate = $changeRateFrom * $changeRateTo;
//            $this->addExchangeRate($from, $to, $changeRate);
//            return true;
//        }
        return false;
    }

    public function getPivotCurrency(): Currency
    {
        return $this->pivotCurrency;
    }
    public function setPivotCurrency(Currency $pivotCurrency): void
    {
        $this->pivotCurrency = $pivotCurrency;
    }

    public function getExchangeRates(): array
    {
        return $this->exchangeRates;
    }

    /*private function updateExchangeRates(): void
    {
        if ($this->pivotCurrency !== null) {
            foreach ($this->exchangeRates as $key => $exchangeRate) {
                [$from, $to] = explode('->', $key);
                if ($from !== $this->pivotCurrency->getValue() && $to !== $this->pivotCurrency->getValue()) {
                    $fromToPivot = $this->exchangeRates[$from . '->' . $this->pivotCurrency->getValue()];
                    $pivotToTo = $this->exchangeRates[$this->pivotCurrency->getValue() . '->' . $to];
                    $this->exchangeRates[$key] = $exchangeRate / $fromToPivot * $pivotToTo;
                }
            }
        }
    }*/
}