<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetResource\Pages;
use App\Filament\Resources\AssetResource\RelationManagers;
use App\Models\Asset;
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

class AssetResource extends Resource
{
    protected static ?string $model = Asset::class;
    protected static ?string $navigationGroup = 'Inventory';

    protected static ?string $modelLabel = 'Item';
    protected static ?string $pluralModelLabel = 'Items';


    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->label('Name')
                ->required(),
                Forms\Components\TextInput::make('description')
                ->label('Description'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('description')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
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
                Tables\Actions\Action::make('assign ')
                ->label('Assign To Store')
                ->action('assignWareHouse')
                ->form([

                Forms\Components\Select::make('warehouse_id')
                ->label('To')
                ->options(WareHouse::all()->pluck('name','id')->toArray())
                ->searchable()
                ->default('1'),
                Forms\Components\TextInput::make('quantity')
                ->numeric()
                ])
                ->visible(function(Asset $record){
                    $user = Auth()->user();
                    if($user->role_id == 1 ) return true;
                }),
                Tables\Actions\Action::make('request ')
                ->label('Request')
                ->action('itemRequest')
                ->form([

                Forms\Components\TextInput::make('quantity')
                ->numeric()
                ])
                ->visible(function(Asset $record){
                    $user = Auth()->user();
                    if($user->role_id == 2 ) return true;
                }) ,
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                ->visible(function(Asset $record){
                    $user = Auth()->user();
                    if($user->role_id == 1 ) return true;
                }),
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
            'index' => Pages\ListAssets::route('/'),
            'create' => Pages\CreateAsset::route('/create'),
            'view' => Pages\ViewAsset::route('/{record}'),
            'edit' => Pages\EditAsset::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        $query =  parent::getEloquentQuery()
        ->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);

        if($user->role_id == 0 ){
         return $query->where('id' ,'>',  0)->where('status',1)->orwhere('status',2)->orwhere('status',4)->orderBy('created_at','desc');
        }
        else if($user->role_id == 1){

        // return AssetList::whereIn('sub_region_id',$subRegionId)->whereIn('status',[1,2,4])->orderBy('created_at','desc');
        return $query->where('id' ,'>',  0)->orderBy('created_at','desc');

        }


        return $query->where('id' ,'>',  0)->orderBy('created_at','desc');

    }


}