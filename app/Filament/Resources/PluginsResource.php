<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PluginsResource\Pages;
use App\Filament\Resources\PluginsResource\RelationManagers;
use App\Models\Consumer;
use App\Models\GatewayService;
use App\Models\Plugin;
use App\Models\Plugins;
use App\Models\PluginServiceRoute;
use App\Models\PluginType;
use App\Models\Route;
use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Psy\VersionUpdater\Checker;

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
                        Select::make('type_plugin')
                            ->label('Plugin')
                            ->searchable()
                            ->reactive()
                            ->options(PluginType::all()->pluck('name', 'id')),
                        Toggle::make('enabled')
                            ->label('This plugin is Enabled')
                            ->onIcon('heroicon-o-power')
                            ->offIcon('heroicon-o-power')
                            ->default(true),
                        Radio::make('apply_to')
                            ->label('Apply to')
                            ->reactive()
                            ->options([
                                'service_routes' => 'Service and Routes',
                                'consumer' => 'Consumer',
                            ])
                            ->default('service_routes')
                            ->inline()
                            ->inlineLabel(false),
                        Select::make('gatewayService')
                            ->label('Service')
                            ->placeholder('Select a service')
                            ->reactive()
                            ->visible(fn(Get $get) => $get('apply_to') === 'service_routes')
                            ->options(function (Get $get) {
                                $services = GatewayService::all()->mapWithKeys(function ($service) {
                                    return [$service->id => "{$service->name} - {$service->id}"];
                                })->toArray();
                                return [-1  => 'Any Service'] + $services;
                            })
                            ->default(-1)
                            ->afterStateUpdated(function (callable $set, Get $get) {
                                $set('routes_id', -1);
                            }),
                        Select::make('routes')
                            ->label('Routes')
                            ->placeholder('Select a Routes')
                            ->visible(fn(Get $get) => $get('apply_to') === 'service_routes')
                            ->options(function (Get $get) {
                                $gatewayId = $get('gatewayService');
                                $routes = Route::where('gateway_id', $gatewayId)->get()->mapWithKeys(function ($routes) {
                                    return [$routes->id => "{$routes->name} - {$routes->id}"];
                                })->toArray();
                                return [-1  => 'Any Routes'] + $routes;
                            })
                            ->default(-1),
                        Select::make('consumers_id')
                            ->label('Consumer')
                            ->placeholder('Select a consumer')
                            ->visible(fn(Get $get) => $get('apply_to') === 'consumer')
                            ->options(Consumer::all()->pluck('username', 'id')),
                        TextInput::make('name')
                            ->label('Name')
                            ->columns(1),
                    ]),
                Section::make('Plugin Configuration')
                    ->description('Configuration parameters for this plugin. View advanced parameters for extended configuration.')
                    ->schema([
                        ...collect(DB::table('plugin_types')->get())->map(function ($plugin_types) {
                            $configs = explode(',', $plugin_types->config);
                            return collect($configs)->map(function ($config) use ($plugin_types) {
                                return TextInput::make($config)
                                    ->label($config ?? ucfirst($config))
                                    ->visible(fn(Get $get) => $get('type_plugin') === $plugin_types->id);
                            })->toArray();
                        })->flatten()->toArray(),
                    ]),
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
                    ->label('Applied To')
                    ->separator(',')
                    ->badge(),
                ToggleColumn::make('enabled')
                    ->label('Enabled'),
                TextColumn::make('ordering')
                    ->label('Ordering'),
                TextColumn::make('tags')
                    ->label('Tags')
                    ->separator(',')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'view' => Pages\ViewPlugins::route('/{record}'),
            'edit' => Pages\EditPlugins::route('/{record}/edit'),
        ];
    }

    public static function setAppliedTo(Model $model): void
    {

        if (!$model instanceof Plugin) {
            return;
        }

        $applied_to = [];

        if ($model->gateway_id) {
            $applied_to[] = 'Service';
        }

        if ($model->routes_id) {
            $applied_to[] = 'Route';
        }

        $model->applied_to = !empty($applied_to) ? implode(',', $applied_to) : 'Global';
        $model->save();
    }
}
