<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    DollarSign,
    Download,
    Package,
    ShoppingCart,
    TrendingUp,
} from '@lucide/vue';

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Dashboard', href: '#' }],
    },
});

const props = defineProps<{
    totalPendapatan: number;
    chartLabels: string[];
    chartData: number[];
    produkPenjualan: { nama: string; kategori: string; terjual: number }[];
    periode: string;
}>();

const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value);
};

const maxChartValue = Math.max(...props.chartData, 1);
const chartHeight = 280;

const getBarHeight = (value: number) => {
    return (value / maxChartValue) * chartHeight;
};

const totalTerjual = props.produkPenjualan.reduce(
    (sum, p) => sum + p.terjual,
    0,
);
const maxTerjual = Math.max(...props.produkPenjualan.map((p) => p.terjual), 1);
const reportUrl = '/dashboard/report/produk-penjualan';
</script>

<template>
    <Head title="Dashboard" />

    <div class="flex flex-col gap-6 p-6">
        <!-- Header -->
        <div
            class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between"
        >
            <div>
                <h1 class="text-2xl font-bold text-foreground">Dashboard</h1>
                <p class="text-sm text-muted-foreground">
                    Ringkasan penjualan toko elektronik
                </p>
            </div>

            <a
                :href="reportUrl"
                class="inline-flex h-10 items-center justify-center gap-2 rounded-md bg-primary px-4 text-sm font-medium text-primary-foreground shadow-sm transition-colors hover:bg-primary/90"
            >
                <Download class="h-4 w-4" />
                Generate Report
            </a>
        </div>

        <!-- Stat Cards -->
        <div class="grid gap-4 md:grid-cols-4">
            <div class="rounded-xl border border-border bg-card p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p
                            class="text-xs font-medium tracking-wide text-muted-foreground uppercase"
                        >
                            Total Pendapatan
                        </p>
                        <p class="mt-1 text-lg font-bold text-foreground">
                            {{ formatCurrency(totalPendapatan) }}
                        </p>
                    </div>
                    <div
                        class="rounded-full bg-green-100 p-3 dark:bg-green-900/30"
                    >
                        <DollarSign
                            class="h-5 w-5 text-green-600 dark:text-green-400"
                        />
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-border bg-card p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p
                            class="text-xs font-medium tracking-wide text-muted-foreground uppercase"
                        >
                            Total Terjual
                        </p>
                        <p class="mt-1 text-lg font-bold text-foreground">
                            {{ totalTerjual }} Unit
                        </p>
                    </div>
                    <div
                        class="rounded-full bg-blue-100 p-3 dark:bg-blue-900/30"
                    >
                        <ShoppingCart
                            class="h-5 w-5 text-blue-600 dark:text-blue-400"
                        />
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-border bg-card p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p
                            class="text-xs font-medium tracking-wide text-muted-foreground uppercase"
                        >
                            Total Produk
                        </p>
                        <p class="mt-1 text-lg font-bold text-foreground">
                            {{ produkPenjualan.length }} Item
                        </p>
                    </div>
                    <div
                        class="rounded-full bg-purple-100 p-3 dark:bg-purple-900/30"
                    >
                        <Package
                            class="h-5 w-5 text-purple-600 dark:text-purple-400"
                        />
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-border bg-card p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p
                            class="text-xs font-medium tracking-wide text-muted-foreground uppercase"
                        >
                            Rata-rata
                        </p>
                        <p class="mt-1 text-lg font-bold text-foreground">
                            {{
                                totalTerjual > 0
                                    ? formatCurrency(
                                          totalPendapatan / totalTerjual,
                                      )
                                    : 'Rp 0'
                            }}
                        </p>
                    </div>
                    <div
                        class="rounded-full bg-orange-100 p-3 dark:bg-orange-900/30"
                    >
                        <TrendingUp
                            class="h-5 w-5 text-orange-600 dark:text-orange-400"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Line Chart: Penjualan -->
            <div
                class="rounded-xl border border-border bg-card p-5 shadow-sm lg:col-span-2"
            >
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-foreground">
                        Grafik Penjualan Barang
                    </h2>
                    <div class="flex gap-1">
                        <Link
                            v-for="p in ['harian', 'mingguan', 'bulanan']"
                            :key="p"
                            :href="`/dashboard?periode=${p}`"
                            class="rounded-md px-3 py-1 text-xs font-medium transition-colors"
                            :class="
                                periode === p
                                    ? 'bg-primary text-primary-foreground'
                                    : 'bg-muted text-muted-foreground hover:bg-muted/80'
                            "
                        >
                            {{ p.charAt(0).toUpperCase() + p.slice(1) }}
                        </Link>
                    </div>
                </div>

                <div class="relative" :style="{ height: chartHeight + 'px' }">
                    <!-- Y-axis labels -->
                    <div
                        class="absolute top-0 left-0 flex h-full flex-col justify-between text-xs text-muted-foreground"
                    >
                        <span>{{ formatCurrency(maxChartValue) }}</span>
                        <span>{{ formatCurrency(maxChartValue / 2) }}</span>
                        <span>Rp 0</span>
                    </div>

                    <!-- Chart area -->
                    <div class="ml-14 flex h-full items-end gap-3">
                        <div
                            v-for="(val, i) in chartData"
                            :key="i"
                            class="flex flex-1 flex-col items-center gap-1"
                        >
                            <span class="text-xs font-medium text-foreground">{{
                                formatCurrency(val)
                            }}</span>
                            <div
                                class="w-full rounded-t-md bg-primary transition-all hover:bg-primary/80"
                                :style="{ height: getBarHeight(val) + 'px' }"
                            ></div>
                        </div>
                    </div>
                </div>

                <!-- X-axis labels -->
                <div class="mt-2 ml-14 flex gap-3">
                    <span
                        v-for="(label, i) in chartLabels"
                        :key="i"
                        class="flex-1 text-center text-xs text-muted-foreground"
                    >
                        {{ label }}
                    </span>
                </div>
            </div>

            <!-- Bar Chart: Perbandingan Produk -->
            <div class="rounded-xl border border-border bg-card p-5 shadow-sm">
                <h2 class="mb-4 text-base font-semibold text-foreground">
                    Perbandingan Penjualan Produk
                </h2>

                <div class="space-y-4">
                    <div
                        v-for="(produk, i) in produkPenjualan"
                        :key="i"
                        class="flex flex-col gap-1"
                    >
                        <div class="flex items-center justify-between text-sm">
                            <span
                                class="max-w-[120px] truncate font-medium text-foreground"
                                >{{ produk.nama }}</span
                            >
                            <span class="text-xs text-muted-foreground"
                                >{{ produk.terjual }} terjual</span
                            >
                        </div>
                        <div
                            class="h-3 w-full overflow-hidden rounded-full bg-muted"
                        >
                            <div
                                class="h-full rounded-full bg-gradient-to-r from-blue-500 to-indigo-500 transition-all"
                                :style="{
                                    width:
                                        (produk.terjual / maxTerjual) * 100 +
                                        '%',
                                }"
                            ></div>
                        </div>
                    </div>

                    <div
                        v-if="produkPenjualan.length === 0"
                        class="py-8 text-center text-sm text-muted-foreground"
                    >
                        Belum ada data penjualan
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
