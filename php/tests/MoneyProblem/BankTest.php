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
        $convertedAmount = $this->bank->addExchangeRate(Currency::EUR(), Currency::USD(), 1.3)
            ->convert(new Money(10, Currency::EUR()), Currency::USD());

        // Assert
        $this->assertEquals(new Money(13, Currency::USD()), $convertedAmount);
    }

    //Define Pivot Currency
    public function test_set_eur_as_pivot()
    {
        // Arrange
        $bank2 = BankBuilder::aBank()
            ->withPivotCurrency(Currency::EUR())
            ->withExchangeRate(1.2, Currency::USD())
            ->build();

        // Assert
        $this->assertEquals(Currency::EUR(), $bank2->getPivotCurrency());
    }


    //Add an exchange rate
    /*public function test_convert_krw_to_usd_with_eur_as_pivot()
    {
        // Arrange
        $bank2 = BankBuilder::aBank()
            ->withPivotCurrency(Currency::EUR())
            ->withExchangeRate(1.2, Currency::USD())
            ->withExchangeRate(1344, Currency::KRW())
            ->build();

        // Act
        $krwAmount = new Money(10000, Currency::KRW());
        $convertedAmount = $bank2->convert($krwAmount, Currency::USD());

        // Assert
        $this->assertEquals(new Money(10000 / 1344 * 1.2, Currency::USD()), $convertedAmount);
    }*/

//    $this->expectExceptionMessage('EUR->KRW');


    public function test_error_for_add_same_exchange_rate_as_pivot(){
        // Assert
        $this->expectExceptionMessage('An Exchange Rate can not be added for the Pivot Currency');

        // Arrange
        $this->bank->addExchangeRate(Currency::EUR(), Currency::USD(), 1.2);
    }

    public function test_error_for_add_exchange_rate_equal_0(){
        // Assert
        $this->expectExceptionMessage('Exchange Rate should be greater than 0');

        // Arrange
        $this->bank->addExchangeRate(Currency::EUR(), Currency::KRW(), 0);
    }

    public function test_error_for_add_exchange_rate_less_than_0(){
        // Assert
        $this->expectExceptionMessage('Exchange Rate should be greater than 0');

        // Arrange
        $this->bank->addExchangeRate(Currency::EUR(), Currency::KRW(), -1);
    }

    public function test_add_a_float_exchange_rate_of_1_298989888(){
        // Act
        $nbElementsBefore = count($this->bank->getExchangeRates());

        // Arrange
        $this->bank = $this->bank->addExchangeRate(Currency::EUR(), Currency::KRW(), 1.298989888);

        // Act
        $nbElementsAfter = count($this->bank->getExchangeRates());

        // Assert
        $this->assertEquals($nbElementsBefore+2, $nbElementsAfter);
        $this->assertEquals(1.298989888, $this->bank->getExchangeRates()[Currency::EUR() .'->'. Currency::KRW()]);
        $this->assertEquals(1/1.298989888, $this->bank->getExchangeRates()[Currency::KRW() .'->'. Currency::EUR()]);
    }

    /*Scenario Update an Exchange Rate
    Given a Bank with EUR as Pivot Currency
    And an Exchange Rate of 1.2 for Currency USD
    When I update an Exchange Rate for the Currency USD to 1.3
    Then the Exchange Rate from EUR to USD should be 1.3*/

    public function test_update_an_exchange_rate_of_1_2_for_USD_to_1_3(){
        // Arrange
        $this->bank = $this->bank->addExchangeRate(Currency::EUR(), Currency::USD(), 1.3);

        // Assert
        $this->assertEquals(1.3, $this->bank->getExchangeRates()[Currency::EUR() .'->'. Currency::USD()]);
    }

}
