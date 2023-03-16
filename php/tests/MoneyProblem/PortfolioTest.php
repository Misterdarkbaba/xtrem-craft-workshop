<?php

namespace Tests\MoneyProblem;

use MoneyProblem\Domain\Bank;
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
        $evaluation = $portfolio->evaluate(Currency::USD(), Bank::create(Currency::EUR(), Currency::USD(), 1.2));

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
        $bank = Bank::create(Currency::USD(), Currency::KRW(), 1100);
        $evaluation = $portfolio->evaluate(Currency::KRW(), $bank);

        // Assert
        $this->assertEquals(2200, $evaluation);
    }
}