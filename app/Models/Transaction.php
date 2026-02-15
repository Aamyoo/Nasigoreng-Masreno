<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_user',
        'tanggal',
        'nomor_meja',
        'mode_pesanan',
        'subtotal',
        'pajak',
        'total',
        'metode_pembayaran',
        'dibayar',
        'kembalian',
        'payment_status',
        'midtrans_order_id',
        'midtrans_snap_token',
        'midtrans_transaction_status'
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'subtotal' => 'integer',
        'pajak' => 'integer',
        'total' => 'integer',
        'dibayar' => 'integer',
        'kembalian' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaksi_id');
    }
}
