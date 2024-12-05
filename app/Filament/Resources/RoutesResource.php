<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoutesResource\Pages;
use App\Filament\Resources\RoutesResource\RelationManagers;
use App\Models\Route;
use App\Models\Routes;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoutesResource extends Resource
{
    protected static ?string $model = Route::class;

    protected static ?int $navigationSort =  4;

    protected static ?string $navigationGroup = 'Gateway Manager';
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('General Information')
                    ->description('General information will help you identify and manage this route')
                    ->aside()
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->placeholder('Enter a unique name'),
                        Select::make('gateway_id')
                            ->label('Service')
                            ->required()
                            ->placeholder('Select a service')
                            ->options(
                                \App\Models\GatewayService::all()->mapWithKeys(function ($service) {
                                    return [$service->id => "{$service->name} - {$service->id}"];
                                })->toArray()
                            ),
                        TextInput::make('tags')
                            ->label('Tags')
                            ->required()
                            ->placeholder('Enter a list of tags separated by commas'),
                    ]),
                Section::make('Route Configuration')
                    ->description('Route configuration determines how this route will handle incoming requests')
                    ->aside()
                    ->schema([
                        Select::make('protocol')
                            ->label('Protocols')
                            ->options([
                                'HTTP' => 'HTTP',
                                'HTTPS' => 'HTTPS',
                                'HTTP,HTTPS' => 'HTTP,HTTPS'
                            ])
                            ->required(),
                        Tabs::make('Tabs')
                            ->tabs([
                                Tabs\Tab::make('Traditional')
                                    ->schema([
                                        // ...
                                    ]),
                                Tabs\Tab::make('Expressions')
                                    ->schema([
                                        // ...
                                    ]),
                            ])
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name'),
                TextColumn::make('protocol')
                    ->label('Protocols'),
                TextColumn::make('host')
                    ->label('Hosts'),
                TextColumn::make('methods')
                    ->label('Methods'),
                TextColumn::make('path')
                    ->label('Paths'),
                TextColumn::make('expression')
                    ->label('Expression'),
                TextColumn::make('tags')
                    ->label('Tags')
                    ->badge(),
                TextColumn::make('updated_at')
                    ->label('Last Modified')
                    ->dateTime('M d, Y, h:i A')
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
            ->emptyStateHeading('Configure a New Route')
            ->emptyStateDescription('Routes proxy requests to an associated Service.')
            ->emptyStateIcon('heroicon-o-globe-alt')
            ->emptyStateActions([
                Tables\Actions\Action::make('create')
                    ->url(fn(): string => RoutesResource::getUrl('create'))
                    ->label('New Route'),
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
