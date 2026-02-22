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
        $paymentType = (string) $request->input('payment_type');

        $paymentStatus = match ($transactionStatus) {
            'settlement', 'capture' => 'paid',
            'pending' => 'pending',
            'expire' => 'expired',
            'cancel' => 'cancelled',
            'deny' => 'failed',
            default => $transaction->payment_status,
        };

        $transaction->update([
            'payment_status' => $paymentStatus,
            'payment_type_midtrans' => $paymentType !== '' ? $paymentType : $transaction->payment_type_midtrans,
            'midtrans_transaction_status' => $transactionStatus,
            'midtrans_response' => $request->all(),
            'dibayar' => $paymentStatus === 'paid' ? $transaction->total : $transaction->dibayar,
            'kembalian' => 0,
        ]);

        return response()->json(['message' => 'ok']);
    }
}
