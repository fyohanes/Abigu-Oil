<?php

namespace App\Filament\Resources\ItemRequestResource\Pages;

use App\Filament\Resources\ItemRequestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditItemRequest extends EditRecord
{
    protected static string $resource = ItemRequestResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
