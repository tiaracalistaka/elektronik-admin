<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $fillable = ['nama', 'kategori_id', 'harga', 'thumbnail', 'link_gambar', 'deskripsi', 'stok'];

    protected $casts = [
        'harga' => 'decimal:0',
        'stok' => 'integer',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function penjalanans(): HasMany
    {
        return $this->hasMany(Penjualan::class);
    }
}
