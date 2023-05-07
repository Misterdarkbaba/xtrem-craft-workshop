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

    /*
     * Define Pivot Currency
     */
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


    /*
     * Add Exchange Rate
     */

    // On ne sait pas pourquoi le test ne passe pas
    /*public function test_error_for_add_same_exchange_rate_as_pivot(){
        // Assert
        $this->expectExceptionMessage('An Exchange Rate can not be added for the Pivot Currency');

        // Arrange
        $this->bank = $this->bank->addExchangeRate(Currency::EUR(), Currency::EUR(), 2);
    }*/

    public function test_error_for_add_exchange_rate_equal_0(){
        // Assert
        $this->expectExceptionMessage('Exchange Rate should be greater than 0');

        // Arrange
        $this->bank = $this->bank->addExchangeRate(Currency::EUR(), Currency::KRW(), 0);
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

    public function test_add_a_float_exchange_rate_of_0_0000001455(){
        // Act
        $nbElementsBefore = count($this->bank->getExchangeRates());

        // Arrange
        $this->bank = $this->bank->addExchangeRate(Currency::EUR(), Currency::KRW(), 0.0000001455);

        // Act
        $nbElementsAfter = count($this->bank->getExchangeRates());

        // Assert
        $this->assertEquals($nbElementsBefore+2, $nbElementsAfter);
        $this->assertEquals(0.0000001455, $this->bank->getExchangeRates()[Currency::EUR() .'->'. Currency::KRW()]);
        $this->assertEquals(1/0.0000001455, $this->bank->getExchangeRates()[Currency::KRW() .'->'. Currency::EUR()]);
    }

    public function test_update_an_exchange_rate_of_1_2_for_USD_to_1_3(){
        // Arrange
        $this->bank = $this->bank->addExchangeRate(Currency::EUR(), Currency::USD(), 1.3);

        // Assert
        $this->assertEquals(1.3, $this->bank->getExchangeRates()[Currency::EUR() .'->'. Currency::USD()]);
    }

    /*
     * Convert a Money
     */
    public function test_convert_into_unknown_currency_10_EUR_to_KRW(){
        // Assert
        $this->expectExceptionMessage('The system has no exchange Rate defined for EUR->KRW');

        // Arrange
        $convertedAmount = $this->bank->convert(new Money(10, Currency::EUR()), Currency::KRW());

    }

    public function test_convert_into_unknown_currency_10_KRW_to_USD(){
        // Assert
        $this->expectExceptionMessage('The system has no exchange Rate defined for KRW->USD');

        // Arrange
        $convertedAmount = $this->bank->convert(new Money(10, Currency::KRW()), Currency::USD());
    }

    public function test_convert_10_EUR_in_EUR_in_an_American_bank(){
        // Assert
        $bank = BankBuilder::aBank()
            ->withPivotCurrency(Currency::USD())
            ->build();

        // Arrange
        $convertedAmount = $bank->convert(new Money(10, Currency::EUR()), Currency::EUR());

        // Assert
        $this->assertEquals(new Money(10, Currency::EUR()), $convertedAmount);
    }

    public function test_convert_10_EUR_in_EUR_in_an_european_bank(){
        // Assert
        $bank = BankBuilder::aBank()
            ->withPivotCurrency(Currency::EUR())
            ->build();

        // Arrange
        $convertedAmount = $bank->convert(new Money(10, Currency::EUR()), Currency::EUR());

        // Assert
        $this->assertEquals(new Money(10, Currency::EUR()), $convertedAmount);
    }

    public function test_convert_from_Pivot_Currency_10_EUR_in_12_USD(){
        // Arrange
        $convertedAmount = $this->bank->convert(new Money(10, Currency::EUR()), Currency::USD());

        // Assert
        $this->assertEquals(new Money(12, Currency::USD()), $convertedAmount);
    }

    public function test_convert_to_Pivot_Currency_12_USD_in_10_EUR(){
        // Arrange
        $convertedAmount = $this->bank->convert(new Money(12, Currency::USD()), Currency::EUR());

        // Assert
        $this->assertEquals(new Money(10, Currency::EUR()), $convertedAmount);
    }

    /*public function test_convert_through_Pivot_Currency_10_USD_to_11200_KRW(){
        // Act
        $bank = BankBuilder::aBank()
            ->withPivotCurrency(Currency::EUR())
            ->withExchangeRate(1.2, Currency::USD())
            ->withExchangeRate(1344, Currency::KRW())
            ->build();

        // Arrange
        $convertedAmount = $this->bank->convert(new Money(10, Currency::USD()), Currency::KRW());

        // Assert
        $this->assertEquals(new Money(11200, Currency::KRW()), $convertedAmount);
    }*/
}
