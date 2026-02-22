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
        'metode_input',
        'payment_type_midtrans',
        'dibayar',
        'kembalian',
        'payment_status',
        'midtrans_order_id',
        'midtrans_snap_token',
        'midtrans_transaction_status',
        'midtrans_response',
        'midtrans_qr_url',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'subtotal' => 'integer',
        'pajak' => 'integer',
        'total' => 'integer',
        'dibayar' => 'integer',
        'kembalian' => 'integer',
        'midtrans_response' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaksi_id');
    }

    public function getMetodePembayaranAttribute(): string
    {
        if ($this->metode_input === 'tunai') {
            return 'Tunai';
        }

        return $this->payment_type_midtrans
            ? strtoupper(str_replace('_', ' ', $this->payment_type_midtrans))
            : 'Midtrans';
    }
}
