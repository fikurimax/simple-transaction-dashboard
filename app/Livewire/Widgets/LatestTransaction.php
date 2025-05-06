<?php

namespace App\Livewire\Widgets;

use App\Models\Transaction;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestTransaction extends BaseWidget
{
    protected static ?string $heading = "Transaksi";

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Transaction::query()->withCount('items')
            )
            ->headerActions([
                Action::make('add')
                    ->label("Buat Transaksi Baru")
                    ->icon('heroicon-o-plus')
            ])
            ->columns([
                Tables\Columns\TextColumn::make('serial')
                    ->label("Serial"),
                Tables\Columns\TextColumn::make('customer_name')
                    ->label("Pelanggan"),
                Tables\Columns\TextColumn::make('customer_address')
                    ->label("Alamat")
                    ->toggledHiddenByDefault()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('total_items')
                    ->alignCenter()
                    ->label("Total Barang")
                    ->toggledHiddenByDefault()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('total_quantity')
                    ->alignCenter()
                    ->label("Total Kuantitas")
                    ->toggledHiddenByDefault()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->alignCenter()
                    ->label("Total Transaksi")
                    ->formatStateUsing(fn($state) => number_format((float) $state, 0, '.', '.')),
                Tables\Columns\TextColumn::make('tax')
                    ->alignCenter()
                    ->label("Pajak")
                    ->formatStateUsing(fn($state) => number_format((float) $state, 0, '.', '.')),
                Tables\Columns\TextColumn::make('cash_in')
                    ->alignCenter()
                    ->label("Uang Masuk")
                    ->formatStateUsing(fn($state) => number_format((float) $state, 0, '.', '.')),
                Tables\Columns\TextColumn::make('change')
                    ->label("Kembalian")
                    ->alignCenter()
                    ->formatStateUsing(fn($state) => number_format((float) $state, 0, '.', '.')),
                Tables\Columns\TextColumn::make('is_debt')
                    ->label("Piutang")
                    ->badge()
                    ->color(function($record) {
                        if ((int) $record->is_debt == 1 && (int) $record->is_debt_paid === 0) {
                            return "danger";
                        } elseif ((int) $record->is_debt == 1 && (int) $record->is_debt_paid === 1) {
                            return "success";
                        }
                        return "-";
                    })
                    ->alignCenter()
                    ->formatStateUsing(function($record) {
                        if ((int) $record->is_debt == 1 && (int) $record->is_debt_paid === 0) {
                            return "Belum Lunas";
                        } elseif ((int) $record->is_debt == 1 && (int) $record->is_debt_paid === 1) {
                            return "Sudah Lunas";
                        }
                        return "-";
                    }),
                Tables\Columns\TextColumn::make('items_count')
                    ->label("Total Barang")
                    ->alignCenter(),
            ])
            ->actions([
                Action::make('print')
                    ->label("Cetak Nota")
                    ->size(ActionSize::Small)
                    ->color('default'),
                Action::make('view')
                    ->label("Lihat")
                    ->size(ActionSize::Small)
                    ->color('default')
            ]);
    }
}
