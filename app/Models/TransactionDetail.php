<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;
    
    protected $table = 'transaksi_detail';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'transaksi_id',
        'menu_id',
        'harga',
        'qty',
        'catatan',
        'subtotal'
    ];
    
    protected $casts = [
        'harga' => 'integer',
        'qty' => 'integer',
        'subtotal' => 'integer'
    ];
    
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaksi_id');
    }
    
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}