<?php

namespace App\Support;

class TransactionCalculator
{
    public const TAX_RATE = 0.10;

    /**
     * @param array<int, array{harga:int, qty:int}> $items
     * @return array{subtotal:int, pajak:int, total:int}
     */
    public static function calculate(array $items): array
    {
        $subtotal = 0;

        foreach ($items as $item) {
            $lineSubtotal = (int) $item['harga'] * (int) $item['qty'];
            $subtotal += $lineSubtotal;
        }

        $pajak = (int) round($subtotal * self::TAX_RATE, 0, PHP_ROUND_HALF_UP);
        $total = $subtotal + $pajak;

        return [
            'subtotal' => $subtotal,
            'pajak' => $pajak,
            'total' => $total,
        ];
    }
}

