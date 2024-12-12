<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoutesResource\Pages;
use App\Filament\Resources\RoutesResource\RelationManagers;
use App\Models\Route;
use App\Models\Routes;
use Doctrine\DBAL\Schema\Schema;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
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
                            ->hintIcon('heroicon-m-information-circle')
                            ->hintIconTooltip('Routes have a protocols property to restrict the client protocol they should listen for.')
                            ->options([
                                'GRPC' => 'GRPC',
                                'GRPCS' => 'GRPCS',
                                'GRPC, GRPCS' => 'GRPC, GRPCS',
                                'HTTP' => 'HTTP',
                                'HTTPS' => 'HTTPS',
                                'HTTP, HTTPS' => 'HTTP, HTTPS',
                                'TCP' => 'TCP',
                                'TLS' => 'TLS',
                                'TLS, UDP' => 'TLS, UDP',
                                'TCP, UDP' => 'TCP, UDP',
                                'TCP, TLS' => 'TCP, TLS',
                                'TCP, TLS, UDP' => 'TCP, TLS, UDP',
                                'TLS_PASSTHROUGH' => 'TLS_PASSTHROUGH',
                                'UDP' => 'UDP',
                                'WS' => 'WS',
                                'WSS' => 'WSS',
                                'WS, WSS' => 'WS, WSS',
                            ])
                            ->required()
                            ->default('HTTP, HTTPS'),
                        Tabs::make('Tabs')
                            ->tabs([
                                Tabs\Tab::make('Traditional')
                                    ->schema([
                                        Repeater::make('paths')
                                            ->simple(
                                                TextInput::make('paths')
                                            )
                                            ->label('Paths')
                                            ->addActionLabel('Add Paths')
                                            ->visible(fn(Get $get) => $get('routing') === 'paths'),
                                        Repeater::make('snis')
                                            ->simple(
                                                TextInput::make('snis')
                                            )
                                            ->label('SNIs')
                                            ->addActionLabel('Add SNIs')
                                            ->visible(fn(Get $get) => $get('routing') === 'snis'),
                                        Repeater::make('hosts')
                                            ->simple(
                                                TextInput::make('hosts')
                                            )
                                            ->label('Hosts')
                                            ->addActionLabel('Add Hosts')
                                            ->visible(fn(Get $get) => $get('routing') === 'hosts'),
                                        Section::make('METHODS')
                                            ->schema([
                                                Toggle::make('get')
                                                    ->label('GET')
                                                    ->onColor('success'),
                                                Toggle::make('patch')
                                                    ->label('PATCH')
                                                    ->onColor('success'),
                                                Toggle::make('head')
                                                    ->label('HEAD')
                                                    ->onColor('success'),
                                                Toggle::make('custom')
                                                    ->label('CUSTOM')
                                                    ->onColor('success'),
                                                Toggle::make('put')
                                                    ->label('PUT')
                                                    ->onColor('success'),
                                                Toggle::make('delete')
                                                    ->label('DELETE')
                                                    ->onColor('success'),
                                                Toggle::make('connect')
                                                    ->label('CONNECT')
                                                    ->onColor('success'),
                                                Toggle::make('post')
                                                    ->label('POST')
                                                    ->onColor('success'),
                                                Toggle::make('options')
                                                    ->label('OPTIONS')
                                                    ->onColor('success'),
                                                Toggle::make('trace')
                                                    ->label('TRACE')
                                                    ->onColor('success'),
                                            ])
                                            ->visible(fn(Get $get) => $get('routing') === 'methods')
                                            ->columns(3),
                                        Repeater::make('headers')
                                            ->label('Headers')
                                            ->schema([
                                                TextInput::make('name')
                                                    ->label('')
                                                    ->placeholder('Enter a header name'),
                                                TextInput::make('value')
                                                    ->label('')
                                                    ->placeholder('Enter a header Value')
                                            ])
                                            ->columns(2)
                                            ->visible(fn(Get $get) => $get('routing') === 'headers'),

                                        Radio::make('routing')
                                            ->label('')
                                            ->inlineLabel(false)
                                            ->options([
                                                'hosts' => 'Hosts',
                                                'methods' => 'Methods',
                                                'paths' => 'Paths',
                                                'headers' => 'Headers',
                                                'snis' => 'SNIs',
                                            ])
                                            ->reactive()
                                            ->columns(5)
                                            ->required(),
                                    ]),
                                Tabs\Tab::make('Expressions')
                                    ->schema([
                                        RichEditor::make('code')
                                            ->label('Routing Rules')
                                            ->toolbarButtons([''])
                                    ]),
                            ]),
                    ]),

                Section::make('View Advanced Fields')
                    ->schema([
                        Select::make('path_handling')
                            ->label('Path Handling')
                            ->default('v0')
                            ->hintIcon('heroicon-m-information-circle')
                            ->hintIconTooltip('This treats service.path, route.path and request path as segments of a URL.')
                            ->options([
                                'v0' => 'v0',
                                'v1' => 'v1',
                            ]),
                        Select::make('redirect_status')
                            ->label('HTTPS Redirect Status Code')
                            ->default('426')
                            ->options([
                                '426' => '426',
                                '301' => '301',
                                '302' => '302',
                                '307' => '307',
                                '308' => '308',
                            ]),
                        TextInput::make('regex')
                            ->label('Regex Priority')
                            ->numeric()
                            ->default('0'),
                        Checkbox::make('strip_path')
                            ->label('Strip Path')
                            ->default(true),
                        Checkbox::make('preserve_host')
                            ->label('Preserve Host'),
                        Checkbox::make('request_buffering')
                            ->label('Request Buffering')
                            ->default(true),
                        Checkbox::make('response_buffering')
                            ->label('Response Buffering')
                            ->default(true)
                    ])
                    ->collapsed()

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name'),
                TextColumn::make('protocol')
                    ->label('Protocols')
                    ->badge()
                    ->color('gray')
                    ->separator(','),
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
