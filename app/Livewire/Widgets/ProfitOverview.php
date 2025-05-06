<?php

namespace App\Livewire\Widgets;

use App\Models\Transaction;
use App\Models\TransactionItem;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProfitOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $dailyBasePriceTransaction = TransactionItem::query()
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->where('transaction_items.created_at', '>=', now()->startOfDay())
            ->where('transaction_items.created_at', '<=', now())
            ->selectRaw("SUM(products.base_price) AS base_price")
            ->first()
            ->base_price;
        $monthlyBasePriceTransaction = TransactionItem::query()
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->where('transaction_items.created_at', '>=', now()->startOfMonth())
            ->where('transaction_items.created_at', '<=', now())
            ->selectRaw("SUM(products.base_price) AS base_price")
            ->first()
            ->base_price;

        $dailyMarginTransaction = TransactionItem::query()
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->where('transaction_items.created_at', '>=', now()->startOfDay())
            ->where('transaction_items.created_at', '<=', now())
            ->selectRaw("SUM(products.sell_price - products.base_price) AS margin")
            ->first()
            ->margin;
        $monthlyMarginTransaction = TransactionItem::query()
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->where('transaction_items.created_at', '>=', now()->startOfMonth())
            ->where('transaction_items.created_at', '<=', now())
            ->selectRaw("SUM(products.sell_price - products.base_price) AS margin")
            ->first()
            ->margin;

        $sumDailyDebt = Transaction::query()
            ->where('created_at', '>=', now()->startOfDay())
            ->where('created_at', '<=', now())
            ->where('is_debt', '=', 1)
            ->selectRaw("SUM(total_items) AS total_items")
            ->first()
            ->total_items;

        $sumMonthlyDebt = Transaction::query()
            ->where('created_at', '>=', now()->startOfMonth())
            ->where('created_at', '<=', now())
            ->where('is_debt', '=', 1)
            ->selectRaw("SUM(total_items) AS total_items")
            ->first()
            ->total_items;

        return [
            Stat::make("Modal", "Rp. " . number_format((float) $dailyBasePriceTransaction ?? 0, 0, '.', '.'))
                ->description("Modal Bulan Ini Rp. " . number_format((float) $monthlyBasePriceTransaction ?? 0, 0, '.', '.')),
            Stat::make("Margin", "Rp. " . number_format((float) $dailyMarginTransaction ?? 0, 0, '.', '.'))
            ->description("Margin Bulan Ini Rp. " . number_format((float) $monthlyMarginTransaction ?? 0, 0, '.', '.')),
            Stat::make("Piutang", "Rp. " . number_format((float) $sumDailyDebt ?? 0, 0, '.', '.'))
                ->description("Piutang Bulan Ini Rp. " . number_format((float) $sumMonthlyDebt ?? 0, 0, '.', '.')),
        ];
    }
}
