<?php

namespace Tests\MoneyProblem;

use MoneyProblem\Domain\Bank;
use MoneyProblem\Domain\Currency;
use MoneyProblem\Domain\Portfolio;
use PHPUnit\Framework\TestCase;

class PortfolioTest extends TestCase
{
    public function  test_should()
    {
        // Arrange
        $portfolio = new Portfolio();

        // Act
        $portfolio->add(5, Currency::USD());
        $portfolio->add(10, Currency::EUR());
        $getEur = $portfolio->getPortfolio()[Currency::EUR()->getValue()];
        $getUSD = $portfolio->getPortfolio()[Currency::USD()->getValue()];

        // Assert
        $this->assertEquals(5, $getUSD);
        $this->assertEquals(10, $getEur);

        $portfolio->add(10, Currency::EUR());

        $this->assertEquals(20, $portfolio->getPortfolio()[Currency::EUR()->getValue()]);
    }

    public function test_should2()
    {
        //        $evaluation = $portfolio->evaluate(Currency::USD(), Bank::create(Currency::EUR(), Currency::USD(), 1.2));
//
//        $this->assertEquals(17, $evaluation);
    }
}