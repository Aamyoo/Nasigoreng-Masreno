<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MidtransCallbackController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $serverKey = (string) config('midtrans.serverKey');
        $orderId = (string) $request->input('order_id');
        $statusCode = (string) $request->input('status_code');
        $grossAmount = (string) $request->input('gross_amount');
        $signatureKey = (string) $request->input('signature_key');

        $expected = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        if (! hash_equals($expected, $signatureKey)) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transaction = Transaction::where('midtrans_order_id', $orderId)->first();
        if (! $transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $transactionStatus = (string) $request->input('transaction_status');
        $fraudStatus = (string) $request->input('fraud_status');

        $paymentStatus = match ($transactionStatus) {
            'settlement', 'capture' => $fraudStatus === 'challenge' ? 'pending' : 'settlement',
            'pending' => 'pending',
            'deny' => 'deny',
            'expire' => 'expire',
            'cancel' => 'cancel',
            default => 'pending',
        };

        $transaction->update([
            'payment_status' => $paymentStatus,
            'midtrans_transaction_status' => $transactionStatus,
            'dibayar' => $paymentStatus === 'settlement' ? $transaction->total : $transaction->dibayar,
            'kembalian' => 0,
        ]);

        return response()->json(['message' => 'ok']);
    }
}
