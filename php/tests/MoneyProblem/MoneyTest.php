<?php

namespace Tests\MoneyProblem;

use MoneyProblem\Domain\Currency;
use MoneyProblem\Domain\MoneyCalculator;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    public function test_add_returns_value()
    {
        $this->assertEquals(15, MoneyCalculator::add(5, 10));
    }

    public function test_multiply_returns_positive_number()
    {
        $this->assertEquals(20, MoneyCalculator::times(10,  2));
    }

    public function test_divide_returns_float()
    {
        $this->assertEquals(1000.5, MoneyCalculator::divide(4002,  4));
    }
}
