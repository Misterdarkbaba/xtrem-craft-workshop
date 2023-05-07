<?php

namespace Tests\MoneyProblem;

use MoneyProblem\Domain\Bank;
use MoneyProblem\Domain\Currency;
use MoneyProblem\Domain\Money;
use MoneyProblem\Exception\MissingExchangeRateException;
use PHPUnit\Framework\TestCase;

class BankTest extends TestCase
{
    private Bank $bank;

    protected function setUp():void
    {
        parent::setUp();
        $this->bank = BankBuilder::aBank()
            ->withPivotCurrency(Currency::EUR())
            ->withExchangeRate(1.2, Currency::USD())
            ->build();
    }


    public function test_convert_eur_to_usd_returns_float()
    {
        // Act
        $convertedAmount = $this->bank->convert(new Money(10, Currency::EUR()), Currency::USD());

        // Assert
        $this->assertEquals(new Money(12, Currency::USD()), $convertedAmount);
    }

    public function test_convert_eur_to_eur_returns_same_value()
    {
        // Act
        $convertedAmount = $this->bank->convert(new Money(10, Currency::EUR()), Currency::EUR());

        // Assert
        $this->assertEquals(new Money(10, Currency::EUR()), $convertedAmount);
    }

    public function test_convert_throws_exception_on_missing_exchange_rate()
    {
        // Assert
        $this->expectException(MissingExchangeRateException::class);
        $this->expectExceptionMessage('EUR->KRW');

        // Act
        $convertedAmount = $this->bank->convert(new Money(10, Currency::EUR()), Currency::KRW());
    }

    public function test_convert_with_different_exchange_rates_returns_different_floats()
    {
        // Act
        $convertedAmount = $this->bank->convert(new Money(10, Currency::EUR()), Currency::USD());

        // Assert
        $this->assertEquals(new Money(12, Currency::USD()), $convertedAmount);

        // Act
        $convertedAmount = $this->bank->addEchangeRate(Currency::EUR(), Currency::USD(), 1.3)
            ->convert(new Money(10, Currency::EUR()), Currency::USD());

        // Assert
        $this->assertEquals(new Money(13, Currency::USD()), $convertedAmount);
    }
}
