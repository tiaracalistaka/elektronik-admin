<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';
    protected $fillable = ['produk_id', 'jumlah', 'total_harga', 'customer_name', 'status'];

    protected $casts = [
        'jumlah' => 'integer',
        'total_harga' => 'decimal:0',
    ];

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }
}
