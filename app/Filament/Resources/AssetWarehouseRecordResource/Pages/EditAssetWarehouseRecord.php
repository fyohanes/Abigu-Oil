<?php

namespace App\Filament\Resources\AssetWarehouseRecordResource\Pages;

use App\Filament\Resources\AssetWarehouseRecordResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAssetWarehouseRecord extends EditRecord
{
    protected static string $resource = AssetWarehouseRecordResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
