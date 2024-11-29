<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DataPlaneNodesResource\Pages;
use App\Filament\Resources\DataPlaneNodesResource\RelationManagers;
use App\Models\DataPlaneNodes;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DataPlaneNodesResource extends Resource
{
    protected static ?string $model = DataPlaneNodes::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';
    protected static ?string $navigationGroup = 'Gateway Manager';

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
            ])
            ->emptyStateHeading('Self-Managed Hybrid Data Plane Nodes')
            ->emptyStateDescription('Use Konnect with a self-hosted gateway in a hybrid configuration.')
            ->emptyStateIcon('heroicon-o-inbox-stack')
            ->emptyStateActions([
                Tables\Actions\Action::make('create')
                    ->url(fn (): string => DataPlaneNodesResource::getUrl('create'))
                    ->label('New DataPlaneNodes'),
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
            'index' => Pages\ListDataPlaneNodes::route('/'),
            'create' => Pages\CreateDataPlaneNodes::route('/create'),
            'edit' => Pages\EditDataPlaneNodes::route('/{record}/edit'),
        ];
    }
}
