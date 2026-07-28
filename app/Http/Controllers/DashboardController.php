<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalPendapatan = Penjualan::where('status', 'completed')->sum('total_harga');

        $periode = $request->get('periode', 'bulanan');
        $labels = [];
        $data = [];

        if ($periode === 'harian') {
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $labels[] = now()->subDays($i)->locale('id')->dayName;
                $data[] = (int) Penjualan::where('status', 'completed')
                    ->whereDate('created_at', $date)
                    ->sum('total_harga');
            }
        } elseif ($periode === 'mingguan') {
            for ($i = 3; $i >= 0; $i--) {
                $weekStart = now()->subWeeks($i)->startOfWeek();
                $weekEnd = now()->subWeeks($i)->endOfWeek();
                $labels[] = 'Mg ' . ($i + 1);
                $data[] = (int) Penjualan::where('status', 'completed')
                    ->whereBetween('created_at', [$weekStart, $weekEnd])
                    ->sum('total_harga');
            }
        } else {
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i)->format('Y-m');
                $labels[] = now()->subMonths($i)->locale('id')->monthName;
                $data[] = (int) Penjualan::where('status', 'completed')
                    ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$month])
                    ->sum('total_harga');
            }
        }

        $produkPenjualan = Produk::with('kategori')
            ->withCount(['penjalanans as total_terjual' => fn($q) => $q->where('status', 'completed')])
            ->whereHas('penjalanans', fn($q) => $q->where('status', 'completed'))
            ->orderByDesc('total_terjual')
            ->get(['id', 'nama', 'kategori_id'])
            ->map(fn($p) => [
                'nama' => $p->nama,
                'kategori' => $p->kategori->nama ?? '-',
                'terjual' => (int) $p->total_terjual,
            ]);

        return inertia('admin/dashboard/Index', [
            'totalPendapatan' => $totalPendapatan,
            'chartLabels' => $labels,
            'chartData' => $data,
            'produkPenjualan' => $produkPenjualan,
            'periode' => $periode,
        ]);
    }
}
