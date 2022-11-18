<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemRequestResource\Pages;
use App\Filament\Resources\ItemRequestResource\RelationManagers;
use App\Models\ItemRequest;
use App\Models\WareHouse;
use Carbon\Carbon;
use Closure;
use Filament\Tables\Filters\Filter;
use Filament\Forms;
use Filament\Tables\Actions\Action;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\Filter as FiltersFilter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemRequestResource extends Resource
{
    protected static ?string $navigationGroup = 'Requests';

    protected static ?string $model = ItemRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('quantity')
                ->numeric()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('seller.name')
                ->sortable()
                ->label('Requested By')
                ->searchable(),
                Tables\Columns\TextColumn::make('admin.name')
                ->sortable()
                ->label('Approved By')
                ->searchable(),
                Tables\Columns\TextColumn::make('asset.name')
                ->sortable()
                ->label('Item')
                ->searchable(),
                Tables\Columns\TextColumn::make('shop.name')
                ->sortable()
                ->label('Shop')
                ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                ->sortable()
                ->label('Quantity')
                ->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                ->colors([
                    'primary'=>fn($state)=>in_array($state,['1']),
                    'success'=>fn($state)=>in_array($state,['2']),
                    'danger'=>fn($state)=>in_array($state,['3']),



                ])
                ->enum([
                    '1'=>'Requested',
                    '2'=>'Approved',
                    '3'=>'Rejected',

                ])
                ->label('Status')
                ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                ->label('Date')
                ->formatStateUsing(fn (string $state): string => Carbon::parse($state)->toDayDateTimeString())
                ->sortable()
                ->searchable()
                ->toggleable(),

            ])
            ->filters([
                SelectFilter::make('status')
                ->options([

                    '1' => __('Requested'),
                    '2' => __('Approved'),
                    '3' => __('Rejected'),


                ])
                ->default('notTrashed')
                ->label('Status')
                ->query(function (Builder $query, $data): Builder {
                    return $query->when(
                        $data['value'],
                        function ($query, $value) {

                            if ($value === '1') {
                                return $query->where('status',$value);
                            }

                            if ($value === '2') {
                                return $query->where('status',$value);
                            }
                            if ($value === '3') {
                                return $query->where('status',$value);
                            }

                            return $query;
                        });
                }),
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
                Action::make('approve')
    ->label('Approve')
    ->action('approve')
    ->requiresConfirmation()
    ->visible(function(ItemRequest $record){
        $user = Auth()->user();
        if($user->role_id == 1 && $record->status ==1) return true;
    })
    ->form([
        Forms\Components\Select::make('warehouse')
        ->options(WareHouse::all()->pluck('name','id'))
        ->default(1)
    ]),
    Action::make('reject')
    ->label('Reject')
    ->action('reject')
    ->requiresConfirmation()
    ->visible(function(ItemRequest $record){
        $user = Auth()->user();
        if($user->role_id == 1 && $record->status ==1) return true;
    })
    ->color('danger'),
                Tables\Actions\EditAction::make()
                ->visible(function(ItemRequest $items){
                    $user = Auth()->user();
                    if($user->role_id == 2 && $items->status ==1)return true ;
                }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListItemRequests::route('/'),
            'create' => Pages\CreateItemRequest::route('/create'),
            'edit' => Pages\EditItemRequest::route('/{record}/edit'),
        ];
    }
}