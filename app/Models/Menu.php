<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    
    protected $table = 'menu';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'nama_menu',
        'harga',
        'kategori',
        'stok',
        'gambar',
        'is_active'
    ];
    
    protected $casts = [
        'harga' => 'integer',
        'stok' => 'integer',
        'is_active' => 'boolean'
    ];
    
    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'menu_id');
    }
}