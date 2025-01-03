<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GatewayServicesResource\Pages;
use App\Filament\Resources\GatewayServicesResource\RelationManagers;
use App\Models\DataPlaneNodes;
use App\Models\GatewayService;
use App\Models\GatewayServices;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GatewayServicesResource extends Resource
{
    protected static ?string $model = GatewayService::class;

    protected static ?int $navigationSort =  3;

    protected static ?string $navigationGroup = 'Gateway Manager';
    protected static ?string $navigationIcon = 'heroicon-o-server-stack';
    protected static ?string $navigationLabel = 'Gateway Services';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('General Information')
                    ->description('General information will help identify and manage this Gateway Service.')
                    ->aside()
                    ->schema([
                        Select::make('data_plane_id')
                            ->label('Data Plane Nodes')
                            ->options(
                                DataPlaneNodes::all()->mapWithKeys(function ($service) {
                                    return [$service->id => "{$service->name} - {$service->id}"];
                                })->toArray()
                            ),
                        TextInput::make('name')
                            ->label('Name')
                            ->placeholder('Enter a unique name')
                            ->regex('/^\S*$/')
                            ->validationMessages([
                                'unique' => 'name - name (type: unique) constraint failed',
                                'regex' => 'The name can be any string containing characters, letters, numbers, or the following characters: ., -, _, or ~. Do not use spaces.'
                            ])
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->hintIcon('heroicon-m-information-circle')
                            ->hintIconTooltip('The Service name'),
                        TextInput::make('tags')
                            ->label('Tags')
                            ->placeholder('Enter a list of tags separated by comma')
                            ->helperText('e.g. tag1, tag2, tag3')
                            ->required()
                            ->hintIcon('heroicon-m-information-circle')
                            ->hintIconTooltip('An optional set of strings associated with the Service for grouping and filtering.'),
                    ]),
                Section::make('Service Endpoint')
                    ->description('Define the endpoint for this service by specifying the full URL or by its separate elements.')
                    ->aside()
                    ->schema([
                        Select::make('protocol')
                            ->label('Protocol')
                            ->default('http')
                            ->required()
                            ->options([
                                'grpc' => [
                                    'grpc' => 'grpc',
                                    'grpcs' => 'grpcs',
                                ],
                                'http' => [
                                    'http' => 'http',
                                    'https' => 'https',
                                ],
                                'tcp' => [
                                    'tcp' => 'tcp',
                                    'tls' => 'tls',
                                    'tls_passthrough' => 'tls_passthrough',
                                ],
                                'udp' => [
                                    'udp' => 'udp',
                                ],
                                'websocket' => [
                                    'ws' => 'ws',
                                    'wss' => 'wss',
                                ]
                            ]),
                            
                        TextInput::make('domain')
                            ->label('Domain')
                            ->required()
                            ->placeholder('Enter a domain'),

                        Section::make('View Advanced Fields')
                            ->schema([
                                TextInput::make('retries')
                                    ->label('Retries')
                                    ->hintIcon('heroicon-m-information-circle')
                                    ->hintIconTooltip('The number of retries to execute upon failure to proxy.')
                                    ->default('5'),
                                TextInput::make('connect_timeout')
                                    ->label('Connection Timeout')
                                    ->hintIcon('heroicon-m-information-circle')
                                    ->hintIconTooltip('The timeout in milliseconds for establishing a connection to the upstream server.')
                                    ->default('60000'),
                                TextInput::make('write_timeout')
                                    ->label('Write Timeout')
                                    ->hintIcon('heroicon-m-information-circle')
                                    ->hintIconTooltip('The timeout in milliseconds between two successive write operations for transmitting a request to the upstream server.')
                                    ->default('60000'),
                                TextInput::make('read_timeout')
                                    ->label('Read Timeout')
                                    ->hintIcon('heroicon-m-information-circle')
                                    ->hintIconTooltip('The timeout in milliseconds between two successive read operations for transmitting a request to the upstream server.')
                                    ->default('60000'),
                                TextInput::make('client_certificate')
                                    ->label('Client Certificate')
                                    ->hintIcon('heroicon-m-information-circle')
                                    ->hintIconTooltip('Certificate to be used as client certificate while TLS handshaking to the upstream server.')
                                    ->placeholder('Enter a Certificate ID'),
                                TextInput::make('ca_certificates')
                                    ->label('CA Certificates')
                                    ->hintIcon('heroicon-m-information-circle')
                                    ->hintIconTooltip("Array of CA Certificate object UUIDs that are used to build the trust store while verifying upstream server's TLS certificate. If set to null when Nginx default is respected. If default CA list in Nginx are not specified and TLS verification is enabled, then handshake with upstream server will always fail (because no CA are trusted).")
                                    ->placeholder('Enter a comma separated list of CA Certificate IDs'),
                                Checkbox::make('tls_verify')
                                    ->label('TLS Verify')
                                    ->hintIcon('heroicon-m-information-circle')
                                    ->hintIconTooltip('Whether to enable verification of upstream server TLS certificate. If set to null, then the Nginx default is respected.'),
                            ])
                            ->collapsed()
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->weight(FontWeight::Bold),
                TextColumn::make('protocol')
                    ->label('Protocol'),
                TextColumn::make('domain')
                    ->label('Domain'),
                ToggleColumn::make('enabled')
                    ->label('Enabled'),
                TextColumn::make('tags')
                    ->label('Tags')
                    ->separator(',')
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
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Configure a New Gateway Service')
            ->emptyStateDescription('Gateway services are used to proxy traffic.')
            ->emptyStateIcon('heroicon-o-server-stack')
            ->emptyStateActions([
                Tables\Actions\Action::make('create')
                    ->url(fn(): string => GatewayServicesResource::getUrl('create'))
                    ->label('New Gateway Service'),
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
            'index' => Pages\ListGatewayServices::route('/'),
            'create' => Pages\CreateGatewayServices::route('/create'),
            'view' => Pages\ViewGatewayServices::route('/{record}'),
            'edit' => Pages\EditGatewayServices::route('/{record}/edit'),
        ];
    }
}
