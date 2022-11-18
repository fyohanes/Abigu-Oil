<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShopAssignedAssetResource\Pages;
use App\Filament\Resources\ShopAssignedAssetResource\RelationManagers;
use App\Models\AssignedUser;
use App\Models\ShopAssignedAsset;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShopAssignedAssetResource extends Resource
{
    protected static ?string $model = ShopAssignedAsset::class;
    protected static ?string $navigationGroup = 'Inventory';

    protected static ?string $modelLabel = 'Shop Assigned Item';
    protected static ?string $pluralModelLabel = 'Shop Assigned Items';

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
                Tables\Columns\TextColumn::make('assets.name')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('assets.description')
                ->sortable()
                ->label('Description')
                ->searchable(),
                Tables\Columns\TextColumn::make('shops.name')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('assigned_quantity')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('wareHouse.name')
                ->sortable()
                ->searchable(),


                Tables\Columns\TextColumn::make('created_at')
                ->label('Date')
                ->formatStateUsing(fn (string $state): string => Carbon::parse($state)->toDayDateTimeString())
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                ->sortable()
                ->label('Total')
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
                Tables\Actions\Action::make('sell ')
                ->label('Sell')
                ->action('Sell')
                ->form([

                Forms\Components\TextInput::make('quantity')
                ->numeric()


                ]) ,
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
            'index' => Pages\ListShopAssignedAssets::route('/'),
            'create' => Pages\CreateShopAssignedAsset::route('/create'),
            'edit' => Pages\EditShopAssignedAsset::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        $query =  parent::getEloquentQuery()
        ->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);

        if($user->role_id == 1 ){
         return $query->where('id' ,'>',  0)->orderBy('created_at','desc');
        }
        else if($user->role_id == 2){
            $shopId = AssignedUser::where('seller_id',$user->id)->pluck('shop_id');

        return $query->whereIn('shop_id' ,$shopId)->orderBy('created_at','desc');

        }


        return $query->where('id' ,'>',  0)->orderBy('created_at','desc');

    }

}
