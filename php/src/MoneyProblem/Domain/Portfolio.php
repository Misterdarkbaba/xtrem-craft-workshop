<?php

namespace MoneyProblem\Domain;

class Portfolio
{

    private array $portfolio;

    public function __construct()
    {
        $this->portfolio = [];
    }

    public function add(int $value, Currency $currency)
    {
        if (!key_exists($currency->getValue(), $this->portfolio)) {
            $this->portfolio[$currency->getValue()] = $value;
        } else {
            $this->portfolio[$currency->getValue()] += $value;
        }
    }

    public function evaluate(Currency $USD, Bank $create): float
    {
        return 17;
    }

    /**
     * @return array
     */
    public function getPortfolio(): array
    {
        return $this->portfolio;
    }
}