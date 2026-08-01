<?php

use App\Models\Kategori;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
});

test('authenticated users can generate product sales report', function () {
    $this->travelTo('2026-07-29 09:30:00');

    $user = User::factory()->create();
    $kategori = Kategori::create(['nama' => 'Smartphone']);

    $iphone = Produk::create([
        'nama' => 'Iphone 13 Pro',
        'kategori_id' => $kategori->id,
        'harga' => 12000000,
        'stok' => 10,
    ]);

    Produk::create([
        'nama' => 'Charger USB-C',
        'kategori_id' => $kategori->id,
        'harga' => 250000,
        'stok' => 20,
    ]);

    Penjualan::create([
        'produk_id' => $iphone->id,
        'jumlah' => 2,
        'total_harga' => 24000000,
        'customer_name' => 'Budi Santoso',
        'status' => 'completed',
        'created_at' => '2026-07-29 09:30:00',
    ]);

    Penjualan::create([
        'produk_id' => $iphone->id,
        'jumlah' => 1,
        'total_harga' => 12000000,
        'customer_name' => 'Siti Rahayu',
        'status' => 'pending',
        'created_at' => '2026-07-29 10:30:00',
    ]);

    $this->actingAs($user);

    $response = $this->get(route('dashboard.report.produk-penjualan'));

    $response->assertOk();
    $response->assertHeader('content-type', 'text/csv; charset=UTF-8');

    $csv = $response->streamedContent();

    expect($csv)
        ->toContain('Produk,Kategori,"Harga Satuan","Total Terjual","Total Pendapatan","Terakhir Terjual"')
        ->toContain('"Iphone 13 Pro",Smartphone,12000000,2,24000000,"2026-07-29 09:30:00"')
        ->toContain('"Charger USB-C",Smartphone,250000,0,0,-');
});
