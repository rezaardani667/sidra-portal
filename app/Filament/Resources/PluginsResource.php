<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PluginsResource\Pages;
use App\Filament\Resources\PluginsResource\RelationManagers;
use App\Models\GatewayService;
use App\Models\Plugin;
use App\Models\Plugins;
use App\Models\PluginServiceRoute;
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
use Psy\VersionUpdater\Checker;

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
                //@todo: type plugin ambil dari db (option), buat migration (type_plugin = id, name, config)
                    ->schema([
                        Select::make('type_plugin')
                            ->label('Plugin')
                            ->searchable()
                            ->reactive()
                            ->options([
                                'basic_auth' => 'Basic Auth',
                                'rate_limit' => 'Rate Limit',
                                'jwt' => 'JWT',
                                'whitelist' => 'Whitelist',
                                'azure_jwt' => 'Azure JWT',
                                'cache' => 'Cache',
                                'rsa' => 'RSA',
                            ]),
                        Toggle::make('enabled')
                            ->label('This plugin is Enabled')
                            ->onIcon('heroicon-o-power')
                            ->offIcon('heroicon-o-power')
                            ->default(true),
                        Select::make('gatewayService')
                            ->label('Service')
                            ->placeholder('Select a service')
                            ->reactive()
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
                            ->options(function (Get $get) {
                                $gatewayId = $get('gatewayService');
                                $routes = Route::where('gateway_id', $gatewayId)->get()->mapWithKeys(function ($routes) {
                                    return [$routes->id => "{$routes->name} - {$routes->id}"];
                                })->toArray();
                                return [-1  => 'Any Routes'] + $routes;
                            })
                            ->default(-1),
                        //@todo: tambah consumer bisa di input (select ambil dari consumer)
                        TextInput::make('name')
                            ->label('Name')
                            ->columns(1),
                        //@todo: config ambil dari db sesuai yang di input foreach textinput
                        Section::make('Plugin Configuration')
                            ->description('Configuration parameters for this plugin. View advanced parameters for extended configuration.')
                            ->visible(fn(Get $get) => $get('type_plugin') === 'jwt')
                            ->schema([
                                select::make('protocols')
                                    ->label('Protocols')
                                    ->multiple()
                                    ->searchable()
                                    ->hintIcon('heroicon-m-information-circle')
                                    ->hintIconTooltip('A list of the request protocols that will trigger this plugin. The default value, as well as the possible values allowed on this field, may change depending on the plugin type.')
                                    ->options([
                                        'grpc' => 'grpc',
                                        'grpcs' => 'grpcs',
                                        'http' => 'http',
                                        'https' => 'https',
                                    ]),
                                Checkbox::make('preflight')
                                    ->label('Run On Preflight')
                                    ->default(true),
                                Checkbox::make('secret_is_base64')
                                    ->label('Secret Is Base64'),
                            ])->collapsible(),

                        Section::make('View Advanced Paramenters')
                            ->visible(fn(Get $get) => $get('type_plugin') === 'jwt')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Instanced Name'),
                                TextInput::make('tags')
                                    ->label('Tags')
                                    ->placeholder('Enter list of tags')
                                    ->helperText('e.g. tag1, tag2, tag3'),
                                TextInput::make('anonymouse')
                                    ->label('Anonymouse'),
                                TextInput::make('verify')
                                    ->label('Claims To Verify'),
                                Repeater::make('Cookie Names')
                                    ->simple(
                                        TextInput::make('name')
                                    ),
                                Repeater::make('Header Names')
                                    ->simple(
                                        TextInput::make('name')
                                    ),
                                TextInput::make('key_claim_name')
                                    ->label('Key Claim Name')
                                    ->default('iss'),
                                TextInput::make('maximum_expiration')
                                    ->label('Maximum Expiration')
                                    ->numeric(),
                                TextInput::make('realm')
                                    ->label('Realm'),
                                Repeater::make('Uri Param Name')
                                    ->simple(
                                        TextInput::make('name')
                                    )
                            ])->collapsed(),
                        Section::make('Request Limits')
                            ->description('Set one or more limits on the number of API requests allowed within a defined timeframe.')
                            ->visible(fn(Get $get) => $get('type_plugin') === 'rate_limit')
                            ->schema([
                                Radio::make('rate_limit')
                                    ->label('Rate Limit Window Type')
                                    ->options([
                                        'sliding' => 'Sliding',
                                        'fixed' => 'Fixed',
                                    ])
                                    ->default('sliding')
                                    ->inline()
                                    ->inlineLabel(false),
                                Repeater::make('limit')
                                    ->label('Limit')
                                    ->schema([
                                        TextInput::make('number')
                                            ->label('')
                                            ->columns(1)
                                            ->placeholder('Request number'),
                                        TextInput::make('time')
                                            ->label('')
                                            ->columns(1)
                                            ->placeholder('Time interval')
                                    ])
                                    ->columns(2),
                            ]),
                        Select::make('identifiers')
                            ->label('Identifiers ')
                            ->required()
                            ->options([
                                'ip' => 'IP',
                                'credential' => 'Credential',
                                'consumer' => 'Consumer',
                                'service' => 'Service',
                                'header' => 'Header',
                                'path' => 'Path',
                                'consumer_group' => 'Consumer Group'
                            ])
                            ->visible(fn(Get $get) => $get('type_plugin') === 'rate_limit')
                            ->default('consumer'),
                        TextInput::make('error_message')
                            ->label('Error Message')
                            ->required()
                            ->numeric()
                            ->default('429')
                            ->visible(fn(Get $get) => $get('type_plugin') === 'rate_limit')
                    ]),
                Section::make('View Advanced Field')
                    ->visible(fn(Get $get) => $get('type_plugin') === 'rate_limit')
                    ->schema([
                        Select::make('protocols')
                            ->label('Protocols')
                            ->required()
                            ->options([
                                'grpc' => 'grpc',
                                'grpcs' => 'grpcs',
                                'http' => 'http',
                                'https' => 'https',
                            ]),
                        TextInput::make('instance_name')
                            ->label('Instance Name'),
                        TextInput::make('tags')
                            ->label('Tags')
                            ->placeholder('Enter list of tags')
                            ->helperText('e.g. tag1, tag2, tag3'),
                        TextInput::make('compound_identifier')
                            ->label('Compound Identifier'),
                        TextInput::make('consumer_groups')
                            ->label('Consumer Groups')
                            ->placeholder('Enter list of Consumer Groups')
                            ->helperText('e.g. group1, group2'),
                        TextInput::make('dictionary_name')
                            ->label('Dictionary Name')
                            ->required()
                            ->default('kong_rate_limiting_counters'),
                        Checkbox::make('disable_penalty')
                            ->label('Disable Penalty'),
                        Checkbox::make('enforce_consumer_groups')
                            ->label('Enforce Consumer Groups'),
                        TextInput::make('header_name')
                            ->label('Header Name'),
                        Checkbox::make('client_headers')
                            ->label('Hide Client Headers'),
                        TextInput::make('lock_dictionary_name')
                            ->label('Lock Dictionary Name')
                            ->default('kong_locks')
                            ->required(),
                        TextInput::make('namespace')
                            ->label('Namespace')
                            ->required(),
                        TextInput::make('path')
                            ->label('Path'),
                        TextInput::make('retry_after_jitter_max')
                            ->label('Retry After Jitter Max')
                            ->numeric(),
                        Select::make('strategy')
                            ->label('Strategy')
                            ->options([
                                'local' => 'local',
                                'redis' => 'redis',
                            ])
                            ->default('local')
                            ->required(),
                        TextInput::make('sync_rate')
                            ->label('Sync Rate')
                            ->numeric(),
                        Select::make('window_type')
                            ->label('Window Type')
                            ->options([
                                'sliding' => 'sliding',
                                'fixed' => 'fixed',
                            ])
                            ->default('sliding')
                    ])->collapsed(),
                Section::make('Plugin Configuration')
                    ->description('Configuration parameters for this plugin. View advanced parameters for extended configuration.')
                    ->visible(fn(Get $get) => $get('type_plugin') === 'basic_auth')
                    ->schema([
                        select::make('protocols')
                            ->label('Protocols')
                            ->multiple()
                            ->searchable()
                            ->hintIcon('heroicon-m-information-circle')
                            ->hintIconTooltip('A list of the request protocols that will trigger this plugin. The default value, as well as the possible values allowed on this field, may change depending on the plugin type.')
                            ->options([
                                'grpc' => 'grpc',
                                'grpcs' => 'grpcs',
                                'http' => 'http',
                                'https' => 'https',
                                'ws' => 'ws',
                                'wss' => 'wss',
                            ]),
                        Checkbox::make('credentials')
                            ->label(' Hide Credentials')
                            ->hintIcon('heroicon-m-information-circle')
                            ->hintIconTooltip('An optional boolean value telling the plugin to show or hide the credential from the upstream service. If true, the plugin will strip the credential from the request (i.e. the Authorization header) before proxying it.'),
                        TextInput::make('realm')
                            ->label('Realm')
                            ->default('service')
                            ->placeholder('Default: service')
                            ->hintIcon('heroicon-m-information-circle')
                            ->hintIconTooltip('When authentication fails the plugin sends WWW-Authenticate header with realm attribute value.'),
                    ])->collapsible(),
                Section::make('View Advanced Paramenters')
                    ->visible(fn(Get $get) => $get('type_plugin') === 'basic_auth')
                    ->schema([
                        TextInput::make('istance_name')
                            ->label('Instance Name')
                            ->hintIcon('heroicon-m-information-circle')
                            ->hintIconTooltip('A custom name for this plugin instance to help identifying from the list view.')
                            ->visible(fn(Get $get) => $get('type_plugin') === 'basic_auth'),
                        TextInput::make('tags')
                            ->label('Tags')
                            ->placeholder('Enter list of tags')
                            ->helperText('e.g. tag1, tag2, tag3')
                            ->hintIcon('heroicon-m-information-circle')
                            ->hintIconTooltip('An optional set of strings for grouping and filtering, separated by commas.')
                            ->visible(fn(Get $get) => $get('type_plugin') === 'basic_auth'),
                        TextInput::make('anonymouse')
                            ->label('Anonymouse')
                            ->hintIcon('heroicon-m-information-circle')
                            ->hintIconTooltip('An optional string (Consumer UUID or username) value to use as an “anonymous” consumer if authentication fails. If empty (default null), the request will fail with an authentication failure 4xx. Please note that this value must refer to the Consumer id or username attribute, and not its custom_id.')
                            ->visible(fn(Get $get) => $get('type_plugin') === 'basic_auth')
                    ])->collapsed()
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
