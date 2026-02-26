<?php

namespace Tests\Unit;

use App\Support\TransactionCalculator;
use PHPUnit\Framework\TestCase;

class TransactionCalculatorTest extends TestCase
{
    public function test_it_calculates_subtotal_tax_and_total_once(): void
    {
        $items = [
            ['harga' => 10000, 'qty' => 2],
            ['harga' => 15000, 'qty' => 1],
        ];

        $result = TransactionCalculator::calculate($items);

        $this->assertSame(35000, $result['subtotal']);
        $this->assertSame(3500, $result['pajak']);
        $this->assertSame(38500, $result['total']);
    }

    public function test_it_never_applies_double_tax(): void
    {
        $items = [
            ['harga' => 12000, 'qty' => 1],
        ];

        $result = TransactionCalculator::calculate($items);

        $this->assertSame(12000, $result['subtotal']);
        $this->assertSame(1200, $result['pajak']);
        $this->assertSame(13200, $result['total']);
    }
}
