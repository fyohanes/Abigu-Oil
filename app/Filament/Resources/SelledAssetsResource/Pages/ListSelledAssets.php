<?php

namespace App\Filament\Resources\SelledAssetsResource\Pages;

use App\Filament\Resources\SelledAssetsResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSelledAssets extends ListRecords
{
    protected static string $resource = SelledAssetsResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}