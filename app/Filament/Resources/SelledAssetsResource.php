<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SelledAssetsResource\Pages;
use App\Filament\Resources\SelledAssetsResource\RelationManagers;
use App\Models\AssignedUser;
use App\Models\SelledAssets;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SelledAssetsResource extends Resource
{
    protected static ?string $model = SelledAssets::class;
    protected static ?string $navigationGroup = 'Records';

    protected static ?string $modelLabel = 'Sold Item';
    protected static ?string $pluralModelLabel = 'Sold Items';


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
                Tables\Columns\TextColumn::make('seller.name')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('asset.name')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('shop.name')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                ->label('Date')
                ->formatStateUsing(fn (string $state): string => Carbon::parse($state)->toDayDateTimeString())
                ->sortable()
                ->searchable()
                ->toggleable(),
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
            'index' => Pages\ListSelledAssets::route('/'),
            'create' => Pages\CreateSelledAssets::route('/create'),
            'edit' => Pages\EditSelledAssets::route('/{record}/edit'),
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