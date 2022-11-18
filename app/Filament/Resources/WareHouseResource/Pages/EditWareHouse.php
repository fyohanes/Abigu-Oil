<?php

namespace App\Filament\Resources\WareHouseResource\Pages;

use App\Filament\Resources\WareHouseResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWareHouse extends EditRecord
{
    protected static string $resource = WareHouseResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
