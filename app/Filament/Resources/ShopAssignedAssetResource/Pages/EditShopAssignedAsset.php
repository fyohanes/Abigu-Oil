<?php

namespace App\Filament\Resources\ShopAssignedAssetResource\Pages;

use App\Filament\Resources\ShopAssignedAssetResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShopAssignedAsset extends EditRecord
{
    protected static string $resource = ShopAssignedAssetResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
