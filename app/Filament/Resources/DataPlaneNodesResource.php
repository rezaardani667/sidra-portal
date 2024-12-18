<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DataPlaneNodesResource\Pages;
use App\Filament\Resources\DataPlaneNodesResource\RelationManagers;
use App\Models\DataPlaneNodes;
use Doctrine\DBAL\Schema\Schema;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DataPlaneNodesResource extends Resource
{
    protected static ?string $model = DataPlaneNodes::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Choose how to Deploy your Gateway')
                    ->description('This will determine how your Gateway will handle traffic and data.')
                    ->aside()
                    ->schema([
                        Radio::make('deployment_models')
                            ->label('')
                            ->options([
                                'standalone' => 'Standalone',
                                'kubernetes' => 'Kubernetes'
                            ])
                            ->descriptions([
                                'standalone' => 'For isolated and independent deployment',
                                'kubernetes' => 'For traffic management native to Kubernetes'
                            ])
                            ->default('serverles'),
                    ]),
                Section::make('General Information')
                    ->description('Add details to help identify and manage your Gateway.')
                    ->aside()
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->placeholder('Enter a unique name')
                            ->required(),
                        Textarea::make('description')
                            ->label('Description')
                            ->placeholder('Describe the purpose of your new control plane')
                    ]),
                Section::make('Advanced Settings')
                    ->schema([
                        Repeater::make('labels')
                            ->label('Labels')
                            ->schema([
                                TextInput::make('name')
                                    ->label('')
                                    ->columns(1)
                                    ->placeholder('Enter a header name'),
                                TextInput::make('value')
                                    ->label('')
                                    ->columns(1)
                                    ->placeholder('Enter a header Value')
                            ])
                            ->columns(2)
                            ->maxItems(5)

                    ])
                    ->collapsed()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Control Planes')
                    ->description(fn(DataPlaneNodes $record): string => $record->description),
                TextColumn::make('deployment_models')
                    ->label('Type'),
                TextColumn::make('data_plane_nodes')
                    ->label('Data Plane Nodes'),
                TextColumn::make('gatewayServices')
                    ->label('Services')
                    ->getStateUsing(fn(DataPlaneNodes $record): int => $record->gatewayServices()->count()),
                ToggleColumn::make('analytics')
                    ->label('Analytics'),
                TextColumn::make('labels')
                    ->label('Labels')
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
                    ->url(fn(): string => DataPlaneNodesResource::getUrl('create'))
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
