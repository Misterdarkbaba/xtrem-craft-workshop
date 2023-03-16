<?php

namespace MoneyProblem\Domain;

class Money
{
    private Currency $currency;
    private int $mount;

    /**
     * @throws \Exception
     */
    public function __construct(int $amount, Currency $currency)
    {
        if ($amount < 0) {
            throw new \Exception('On ne peut pas ajouter un montant négatif');
        }
        $this->mount = $amount;
        $this->currency = $currency;
    }

    public function times(int $multiply): Money
    {
        return new Money($this->mount * $multiply, $this->currency);
    }

    public function add(Money $money): Money
    {
        if ($money->currency->getValue() !== $this->currency->getValue()) {
            throw new \Exception('Une monnaie en ' . $money->currency .
                ' ne peut pas être ajoutée à une monnaie en ' . $this->currency);
        }
        return new Money($this->mount + $money->mount, $this->currency);
    }

    /**
     * @throws \Exception
     */
    public function divide(int $divide): ?Money
    {
        if ($divide < 0) {
            throw new \Exception('Division par un nombre négatif impossible');
        } else if ($divide === 0) {
            throw new \DivisionByZeroError('Division par 0 impossible');
        }
        return new Money($this->mount / $divide, $this->currency);
    }

    public function getAmount(): float
    {
        return $this->mount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    private function setCurrency(Currency $currency): void
    {
        $this->currency = $currency;
    }
}