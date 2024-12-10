<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PluginsResource\Pages;
use App\Filament\Resources\PluginsResource\RelationManagers;
use App\Models\Plugin;
use App\Models\Plugins;
use App\Models\Route;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use function Laravel\Prompts\select;

class PluginsResource extends Resource
{
    protected static ?string $model = Plugin::class;
    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';
    protected static ?string $navigationGroup = 'Gateway Manager';
    protected static ?int $navigationSort =  6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Toggle::make('enabled')
                            ->label('This plugin is Enabled')
                            ->onIcon('heroicon-o-power')
                            ->offIcon('heroicon-o-power')
                            ->default(true),
                        Radio::make('plugin')
                            ->label('')
                            ->reactive()
                            ->required()
                            ->inline()
                            ->inlineLabel(false)
                            ->columnSpanFull()
                            ->options([
                                'global' => 'Global',
                                'scoped' => 'Scoped'
                            ])
                            ->descriptions([
                                'global' => 'All services, routes, and consumers',
                                'scoped' => 'Specific Gateway Services and/or Routes'
                            ]),
                        Select::make('gateway_id')
                            ->label('Service')
                            ->required()
                            ->placeholder('Select a service')
                            ->visible(fn(Get $get) => $get('plugin') === 'scoped')
                            ->options(
                                \App\Models\GatewayService::all()->mapWithKeys(function ($service) {
                                    return [$service->id => "{$service->name} - {$service->id}"];
                                })->toArray()
                            ),
                        Select::make('routes_id')
                            ->label('Routes')
                            ->required()
                            ->placeholder('Select a Routes')
                            ->visible(fn(Get $get) => $get('plugin') === 'scoped')
                            ->options(
                                Route::all()->mapWithKeys(function ($routes) {
                                    return [$routes->id => "{$routes->name} "];
                                })->toArray()
                            ),
                        TextInput::make('name')
                            ->label('Name')
                            ->columns(1),
                        Select::make('type_plugin')
                            ->label('Plugin')
                            ->columns(1)
                            ->options([
                                'Basic Auth' => 'Basic Auth',
                                'Rate Limit' => 'Rate Limit',
                                'Whitelist' => 'Whitelist',
                                'JWT' => 'JWT',
                                'Cache' => 'Cache',
                                'RSA' => 'RSA',
                            ]),
                    ])
                    ->columns(2),
                Section::make('Plugin Configuration')
                    ->description('Configuration parameters for this plugin. View advanced parameters for extended configuration.')
                    ->schema([
                        select::make('protocols')
                            ->label('Protocols')
                            // ->multiple()
                            ->searchable()
                            ->options([
                                'grpc' => 'grpc',
                                'grocs' => 'grpcs',
                                'http' => 'http',
                                'https' => 'https',
                            ]),
                        Checkbox::make('credentials')
                            ->label('Credentials'),
                        Checkbox::make('preflight continue')
                            ->label('Preflight Continue'),
                        Checkbox::make('private network')
                            ->label('Private Network')
                    ])
                    ->collapsible()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name'),
                TextColumn::make('type_plugin')
                    ->label('Plugin'),
                TextColumn::make('applied_to')
                    ->label('Applied To'),
                ToggleColumn::make('enabled')
                    ->label('Enabled'),
                TextColumn::make('ordering')
                    ->label('Ordering')
                    ->default('Static'),
                TextColumn::make('tags')
                    ->label('Tags')
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
            ->emptyStateHeading('Configure a New Plugin')
            ->emptyStateDescription('Plugins are used to extend Kong functionality.')
            ->emptyStateIcon('heroicon-o-puzzle-piece')
            ->emptyStateActions([
                Tables\Actions\Action::make('create')
                    ->url(fn(): string => PluginsResource::getUrl('create'))
                    ->label('New Plugin'),
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
