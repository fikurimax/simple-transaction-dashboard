<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Product;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Support\Enums\Alignment;

class ManageProducts extends ManageRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label("Tambah")
                ->modalHeading("Tambah Produk")
                ->modalFooterActionsAlignment(Alignment::End)
                ->mutateFormDataUsing(function ($data) {
                    if (empty($data['sku'])) {
                        $products = Product::query()->latest()->first();
                        if ($products) {
                            [$category, $itemNumber, $uniqueCode] = explode("-", $products->sku);
                        } else {
                            $itemNumber = 1000;
                        }
                        preg_match_all('/\b\w/', $data['category'], $matches);
                        $data['sku'] = (count($matches) > 0 ? implode('', $matches[0]) : "SK") . "-" . ((int) $itemNumber + 1) . "-" . chr(random_int(65, 90)).chr(random_int(65, 90)).chr(random_int(65, 90));
                    }
                    $data['base_price'] = str_replace('.', '', $data['base_price']);
                    $data['sell_price'] = str_replace('.', '', $data['sell_price']);
                    $data['submitted_by'] = auth()->id();
                    return $data;
                }),
        ];
    }
}
