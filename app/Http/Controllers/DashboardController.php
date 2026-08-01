<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
                $labels[] = 'Mg '.($i + 1);
                $data[] = (int) Penjualan::where('status', 'completed')
                    ->whereBetween('created_at', [$weekStart, $weekEnd])
                    ->sum('total_harga');
            }
        } else {
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $labels[] = $month->locale('id')->monthName;
                $data[] = (int) Penjualan::where('status', 'completed')
                    ->whereBetween('created_at', [
                        $month->copy()->startOfMonth(),
                        $month->copy()->endOfMonth(),
                    ])
                    ->sum('total_harga');
            }
        }

        $produkPenjualan = Produk::with('kategori')
            ->withSum(['penjalanans as total_terjual' => fn ($q) => $q->where('status', 'completed')], 'jumlah')
            ->whereHas('penjalanans', fn ($q) => $q->where('status', 'completed'))
            ->orderByDesc('total_terjual')
            ->get(['id', 'nama', 'kategori_id'])
            ->map(fn ($p) => [
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

    public function exportProdukPenjualan()
    {
        $fileName = 'laporan-penjualan-produk-'.now()->format('Y-m-d').'.csv';

        $produks = Produk::query()
            ->leftJoin('kategori', 'kategori.id', '=', 'produk.kategori_id')
            ->leftJoin('penjualan', function ($join) {
                $join->on('penjualan.produk_id', '=', 'produk.id')
                    ->where('penjualan.status', '=', 'completed');
            })
            ->selectRaw('
                produk.nama,
                kategori.nama as kategori,
                produk.harga,
                COALESCE(SUM(penjualan.jumlah), 0) as total_terjual,
                COALESCE(SUM(penjualan.total_harga), 0) as total_pendapatan,
                MAX(penjualan.created_at) as terakhir_terjual
            ')
            ->groupBy('produk.id', 'produk.nama', 'kategori.nama', 'produk.harga')
            ->orderByDesc('total_pendapatan')
            ->orderBy('produk.nama')
            ->get();

        return response()->streamDownload(function () use ($produks) {
            $handle = fopen('php://output', 'w');

            if ($handle === false) {
                return;
            }

            fputcsv($handle, [
                'Produk',
                'Kategori',
                'Harga Satuan',
                'Total Terjual',
                'Total Pendapatan',
                'Terakhir Terjual',
            ]);

            foreach ($produks as $produk) {
                fputcsv($handle, [
                    $produk->nama,
                    $produk->kategori ?? '-',
                    (int) $produk->harga,
                    (int) $produk->total_terjual,
                    (int) $produk->total_pendapatan,
                    $produk->terakhir_terjual !== null
                        ? Carbon::parse($produk->terakhir_terjual)->format('Y-m-d H:i:s')
                        : '-',
                ]);
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
