<?php

namespace App\Filament\Resources\SelledAssetsResource\Pages;

use App\Filament\Resources\SelledAssetsResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSelledAssets extends EditRecord
{
    protected static string $resource = SelledAssetsResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
