<?php

namespace App\Livewire\Widgets;

use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SellingOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $countDailyTransaction = Transaction::query()
            ->where('created_at', '>=', now()->startOfDay())
            ->where('created_at', '<=', now())
            ->count();
        $countMonthlyTransaction = Transaction::query()
            ->where('created_at', '>=', now()->startOfMonth())
            ->where('created_at', '<=', now())
            ->count();

        $sumDailyTransaction = Transaction::query()
            ->where('created_at', '>=', now()->startOfDay())
            ->where('created_at', '<=', now())
            ->selectRaw("SUM(total_price) AS total_price")
            ->first()
            ->total_price;

        $sumMonthlyTransaction = Transaction::query()
            ->where('created_at', '>=', now()->startOfMonth())
            ->where('created_at', '<=', now())
            ->selectRaw("SUM(total_price) AS total_price")
            ->first()
            ->total_price;

        $sumDailySoldItems = Transaction::query()
            ->where('created_at', '>=', now()->startOfDay())
            ->where('created_at', '<=', now())
            ->selectRaw("SUM(total_items) AS total_items")
            ->first()
            ->total_items;

        $sumMonthlySoldItems = Transaction::query()
            ->where('created_at', '>=', now()->startOfMonth())
            ->where('created_at', '<=', now())
            ->selectRaw("SUM(total_items) AS total_items")
            ->first()
            ->total_items;

        return [
            Stat::make("Jumlah Penjualan", $countDailyTransaction ?? 0)
                ->description("Jumlah Penjualan Bulan Ini {$countMonthlyTransaction}"),
            Stat::make("Total Penjualan", "Rp. " . number_format((float) $sumDailyTransaction ?? 0, 0, '.', '.'))
                ->description("Total Penjualan Bulan Ini Rp. " . number_format((float) $sumMonthlyTransaction ?? 0, 0, '.', '.')),
            Stat::make("Jumlah Barang Terjual", $sumMonthlySoldItems)
                ->description("Jumlah Barang Terjual Bulan Ini {$sumMonthlySoldItems}"),
        ];
    }
}
