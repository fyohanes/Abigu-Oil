<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetWarehouseRecordResource\Pages;
use App\Filament\Resources\AssetWarehouseRecordResource\RelationManagers;
use App\Models\AssetWarehouseRecord;
use App\Models\Shop;
use App\Models\WareHouse;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssetWarehouseRecordResource extends Resource
{
    protected static ?string $model = AssetWarehouseRecord::class;
    protected static ?string $navigationGroup = 'Inventory';

    protected static ?string $modelLabel = 'Available Item';
    protected static ?string $pluralModelLabel = 'Available Items';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('asset.name')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('wareHouse.name')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('updated_at')
                ->label('Date')
                ->formatStateUsing(fn (string $state): string => Carbon::parse($state)->toDayDateTimeString())
                ->sortable()
                ->searchable(),
            ])
            ->filters([
                Filter::make('created_at')
                ->form([
                    Forms\Components\DatePicker::make('created_from')->label('From'),
                    Forms\Components\DatePicker::make('created_until')->label('Until'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['created_from'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['created_until'],
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                })

            ])
            ->actions([
                // Tables\Actions\Action::make('assign ')
                // ->label('Assign To Shop')
                // ->action('assignShop')
                // ->form([
                //     Forms\Components\Select::make('shop_id')
                // ->label('Shop')
                // ->options(Shop::all()->pluck('name','id')->toArray())
                // ->searchable()
                // ->reactive()
                // ->default('1'),
                // Forms\Components\Select::make('warehouse_id')
                // ->label('From')
                // ->options(WareHouse::all()->pluck('name','id')->toArray())
                // ->searchable()
                // ->reactive()
                // ->default('1'),
                // Forms\Components\TextInput::make('quantity')
                // ->numeric()



                // ])
                // ->visible(function(){
                //     $user = Auth()->user();
                //     if($user->role_id == 1 ) return true;
                // }) ,
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssetWarehouseRecords::route('/'),
            'create' => Pages\CreateAssetWarehouseRecord::route('/create'),
            'edit' => Pages\EditAssetWarehouseRecord::route('/{record}/edit'),
        ];
    }
}