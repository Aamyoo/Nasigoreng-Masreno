<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $qrUrl = (string) ($request->input('actions.0.url') ?? $request->input('qr_url') ?? '');

        if (in_array($paymentStatus, ['deny', 'expire', 'cancel'], true)) {
            $this->rollbackAndDeleteTransaction($transaction);

            return response()->json(['message' => 'ok']);
        }

        $transaction->update([
            'payment_status' => $paymentStatus,
            'midtrans_transaction_status' => $transactionStatus,
            'midtrans_qr_url' => $qrUrl !== '' ? $qrUrl : $transaction->midtrans_qr_url,
            'dibayar' => $paymentStatus === 'settlement' ? $transaction->total : $transaction->dibayar,
            'kembalian' => 0,
        ]);

        return response()->json(['message' => 'ok']);
    }

    private function rollbackAndDeleteTransaction(Transaction $transaction): void
    {
        DB::transaction(function () use ($transaction): void {
            $transaction->loadMissing('details');

            foreach ($transaction->details as $detail) {
                Menu::where('id', $detail->menu_id)->increment('stok', $detail->qty);
            }

            TransactionDetail::where('transaksi_id', $transaction->id)->delete();
            $transaction->delete();
        });
    }
}
