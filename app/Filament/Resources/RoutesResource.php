<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoutesResource\Pages;
use App\Filament\Resources\RoutesResource\RelationManagers;
use App\Models\Routes;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoutesResource extends Resource
{
    protected static ?string $model = Routes::class;
    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?string $navigationGroup = 'Gateway Manager';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->placeholder('Enter a unique name'),
                TextInput::make('service')
                    ->label('Service')
                    ->required()
                    ->placeholder('Select a service'),
                TextInput::make('tags')
                    ->label('Tags')
                    ->required()
                    ->placeholder('Enter a list of tags separated by commas'),
                Select::make('protocols')
                    ->label('Protocols')
                    ->options([
                        'HTTP' => 'HTTP',
                        'HTTPS' => 'HTTPS',
                        'HTTP,HTTPS' => 'HTTP,HTTPS'
                    ])
                    ->multiple()
                    ->required(),
                Repeater::make('paths')
                        ->label('HTTP / HTTPS Routing Rules')
                        ->schema([
                            TextInput::make('path')
                                ->label('Paths')
                                ->placeholder('Enter a paths')
                                ->required(),
                        ])
                        ->createButtonLabel('Add Path')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name'),
                TextColumn::make('service')
                    ->label('Service'),
                TextColumn::make('tags')
                    ->label('Tags'),
                TextColumn::make('protocols')
                    ->label('Protocols'),
                TextColumn::make('path')
                    ->label('Path')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListRoutes::route('/'),
            'create' => Pages\CreateRoutes::route('/create'),
            'edit' => Pages\EditRoutes::route('/{record}/edit'),
        ];
    }
}
