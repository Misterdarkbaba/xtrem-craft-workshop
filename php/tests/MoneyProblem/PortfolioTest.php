<?php

namespace Tests\MoneyProblem;

use MoneyProblem\Domain\Currency;
use MoneyProblem\Domain\Money;
use MoneyProblem\Domain\Portfolio;
use PHPUnit\Framework\TestCase;

class PortfolioTest extends TestCase
{
    public function test_should_return_portfolio_in_usd_with_eur_and_usd()
    {
        // Arrange
        $portfolio = new Portfolio();
        $portfolio->add(new Money(5, Currency::USD()));
        $portfolio->add(new Money(10, Currency::EUR()));

        // Act
        $bank = BankBuilder::aBank()
            ->whithPivotCurrency(Currency::EUR())
            ->whithExangeRate(1.2, Currency::USD())
            ->build();
        $evaluation = $portfolio->evaluate(Currency::USD(), $bank);

        // Assert
        $this->assertEquals(17, $evaluation);
    }

    public function test_should_return_portfolio_in_krw_with_usd_and_krw()
    {
        // Arrange
        $portfolio = new Portfolio();
        $portfolio->add(new Money(1, Currency::USD()));
        $portfolio->add(new Money(1100, Currency::KRW()));

        // Act
        $bank = BankBuilder::aBank()
            ->whithPivotCurrency(Currency::USD())
            ->whithExangeRate(1100, Currency::KRW())
            ->build();
        $evaluation = $portfolio->evaluate(Currency::KRW(), $bank);

        // Assert
        $this->assertEquals(2200, $evaluation);
    }

    public function test_portfolio_empty_should_return_0(){
        // Arrange
        $portfolio = new Portfolio();

        // Act
        $bank = BankBuilder::aBank()
            ->whithPivotCurrency(Currency::EUR())
            ->whithExangeRate(1.2, Currency::USD())
            ->build();
        $evaluation = $portfolio->evaluate(Currency::USD(), $bank);

        // Assert
        $this->assertEquals(0, $evaluation);
    }

}