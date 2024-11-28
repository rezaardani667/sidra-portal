<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PluginsResource\Pages;
use App\Filament\Resources\PluginsResource\RelationManagers;
use App\Models\Plugin;
use App\Models\Plugins;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PluginsResource extends Resource
{
    protected static ?string $model = Plugin::class;
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $navigationGroup = 'Gateway Manager';
    protected static ?int $navigationSort =  6;

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
                //
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
            'index' => Pages\ListPlugins::route('/'),
            'create' => Pages\CreatePlugins::route('/create'),
            'edit' => Pages\EditPlugins::route('/{record}/edit'),
        ];
    }
}
