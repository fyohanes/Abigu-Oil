<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Role;
use App\Models\Shop;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Pages\Page;
use Filament\Resources\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationGroup = 'Users';


    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('User Detail')
                ->schema([

                Forms\Components\TextInput::make('name')
                ->label('Name')
                ->required(),
                Forms\Components\TextInput::make('phone')
                ->label('Phone')
                ->required()
                ->unique(ignoreRecord:true),
                Forms\Components\TextInput::make('email')
                ->label('Email')
                ->unique(ignoreRecord:true)
                ->required(),
                Forms\Components\Select::make('role_id')
                        ->label('Role')
                        ->options(Role::all()->pluck('name','id'))
                        ->reactive()
                        //->default(1)
                        ->required(),
                Forms\Components\Select::make('shop_id')
                        ->label('Shop')
                        ->options(Shop::all()->pluck('name','id'))
                        ->reactive()
                        ->required()
                        ->hidden(fn (Closure $get)=> $get('role_id') !=2),

                Forms\Components\TextInput::make('password')
                        ->password()
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                        ->dehydrated(fn ($state) => filled($state))
                        ->required()
                        ->visible(fn (Page $livewire) => ($livewire instanceof CreateRecord))
                        ->required(fn (Page $livewire) => ($livewire instanceof CreateRecord)),


            ])->columns(2)
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->sortable()
                ->searchable()
                ->toggleable(),
                Tables\Columns\TextColumn::make('phone')
                ->sortable()
                ->searchable()
                ->toggleable(),
                Tables\Columns\TextColumn::make('email')
                ->sortable()
                ->searchable()
                ->toggleable(),
                Tables\Columns\TextColumn::make('Role.name')
                ->searchable(),
                Tables\Columns\TextColumn::make('shopUser.shop.name')
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
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

}
