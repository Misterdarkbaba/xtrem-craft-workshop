<?php

namespace Tests\MoneyProblem;

use MoneyProblem\Domain\Currency;
use MoneyProblem\Domain\Money;
use MoneyProblem\Domain\MoneyCalculator;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertEquals;

class MoneyTest extends TestCase
{
    public function test_add_returns_value()
    {
        $this->assertEquals(15, MoneyCalculator::add(5, 10));
    }

    public function test_multiply_returns_positive_number()
    {
        $money = new Money(10, Currency::EUR());
        $multiplied = $money->times(2);

        $this->assertEquals(new Money(20, Currency::EUR()), $multiplied);

        $this->assertEquals(20, MoneyCalculator::times(10,  2));
    }

    public function test_divide_returns_float()
    {
        $this->assertEquals(1000.5, MoneyCalculator::divide(4002,  4));
    }

    public function test_divide_by_zero()
    {
        $money = new Money(10, Currency::EUR());

        $this->expectException(\DivisionByZeroError::class);
        $money->divide(0);
    }

    public function test_divide_by_negative_number()
    {
        $money = new Money(10, Currency::EUR());

        $this->expectException(\Exception::class);
        $money->divide(-2);
    }

    public function test_divide_by_positive_number()
    {
        $money = new Money(10, Currency::EUR());

        $divided = $money->divide(2);

        $this->assertEquals(new Money(5, Currency::EUR()), $divided);
    }

    public function test_add()
    {
        $money = new Money(10, Currency::EUR());
        $added = $money->add(new Money(2, Currency::EUR()));

        $this->assertEquals(new Money(12, Currency::EUR()), $added);
    }

    public function test_add_different_currency()
    {
        $this->expectException(\Exception::class);

        $money = new Money(10, Currency::EUR());
        $money->add(new Money(2, Currency::USD()));
    }

    public function test_negative_mount()
    {
        $this->expectException(\Exception::class);
        
        $money = new Money(-5, Currency::EUR());
    }
}
