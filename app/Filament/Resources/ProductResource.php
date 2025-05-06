<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $modelLabel = "Produk";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('product_name')
                    ->label("Nama Produk")
                    ->columnSpan(2)
                    ->required(),
                Grid::make(3)
                    ->schema([
                        Forms\Components\TextInput::make('sku')
                            ->label("SKU")
                            ->helperText("Kosongkan untuk SKU otomatis")
                            ->placeholder(function (): string {
                                return "SK-" . random_int(1000, 999999) . "-" . strtoupper(chr(random_int(65, 90)));
                            }),
                            Forms\Components\TextInput::make('stock')
                                ->required()
                                ->label("Jumlah Stok")
                                ->default(10)
                                ->numeric()
                                ->mask('99999'),
                            Forms\Components\TextInput::make('unit')
                                ->label("Satuan stok")
                                ->datalist(['pc', 'kg', 'm', 'pack'])
                    ]),
                Grid::make()
                    ->schema([
                        Forms\Components\TextInput::make("category")
                            ->required()
                            ->label("Kategori"),
                        Forms\Components\TextInput::make('brand')
                            ->required()
                            ->label("Merk"),
                    ]),
                Forms\Components\TextInput::make('base_price')
                    ->required()
                    ->label("Harga Modal (per satuan)")
                    ->mask(RawJs::make(<<<'JS'
                        $money($input, ',', '.', 0)
                    JS)),
                Forms\Components\TextInput::make('sell_price')
                    ->required()
                    ->label("Harga Jual (per satuan)")
                    ->mask(RawJs::make(<<<'JS'
                        $money($input, ',', '.', 0)
                    JS)),
                Forms\Components\FileUpload::make('pictures')
                    ->label("Gambar Produk")
                    ->directory('products')
                    ->columnSpanFull()
                    ->multiple()
                    ->image()
                    ->imageEditor()
                    ->panelLayout('grid')
                    ->reorderable()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('product_name')
                    ->label("Nama Barang")
                    ->searchable(),
                Tables\Columns\TextColumn::make('sku')
                    ->label("SKU")
                    ->searchable(),
                Tables\Columns\TextColumn::make('stock')
                    ->label("Stok")
                    ->formatStateUsing(fn($record) => "{$record->stock} {$record->unit}"),
                Tables\Columns\TextColumn::make('brand')
                    ->label("Merk")
                    ->searchable(),
                Tables\Columns\TextColumn::make('category')
                    ->label("Kategori")
                    ->searchable(),
                Tables\Columns\TextColumn::make('base_price')
                    ->label('Harga Modal')
                    ->formatStateUsing(fn($state) => 'Rp. ' . number_format((int) $state, 0, ',', '.'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('sell_price')
                    ->label('Harga Jual')
                    ->formatStateUsing(fn($state) => 'Rp. ' . number_format((int) $state, 0, ',', '.'))
                    ->sortable(),
                Tables\Columns\ImageColumn::make('pictures')
                    ->label("Gambar")
                    ->wrap()
                    ->stacked()
                    ->circular()
                    ->limit(3)
                    ->openUrlInNewTab()
                    ->limitedRemainingText()
                    ->extraImgAttributes([
                        'loading' => 'lazy'
                    ])
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProducts::route('/'),
        ];
    }
}
