<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoutesResource\Pages;
use App\Filament\Resources\RoutesResource\RelationManagers;
use App\Models\Route;
use Doctrine\DBAL\Schema\Schema;
use Filament\Forms;
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
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
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
                            ->regex('/^\S*$/')
                            ->validationMessages([
                                'unique' => 'name - name (type: unique) constraint failed',
                                'regex' => 'The name can be any string containing characters, letters, numbers, or the following characters: ., -, _, or ~. Do not use spaces.'
                            ])
                            ->unique()
                            ->placeholder('Enter a unique name'),
                        Select::make('gateway_id')
                            ->label('Service')
                            ->required()
                            ->searchable()
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
                                        Toggle::make('routing1')
                                            ->label('Paths')
                                            ->reactive()
                                            ->default(false),
                                        Repeater::make('paths')
                                            ->simple(
                                                TextInput::make('paths')
                                            )
                                            ->label('Paths')
                                            ->regex('/\//')
                                            ->validationMessages([
                                                'regex' => 'path - invalid path: must begin with `/` and should not include characters outside of the reserved list of RFC 3986'
                                            ])
                                            ->addActionLabel('Add Paths')
                                            ->visible(fn(Get $get) => $get('routing1') === true),
                                        Toggle::make('routing2')
                                            ->label('Hosts')
                                            ->reactive()
                                            ->default(false),
                                        Repeater::make('hosts')
                                            ->simple(
                                                TextInput::make('hosts')
                                            )
                                            ->label('Hosts')
                                            ->addActionLabel('Add Hosts')
                                            ->visible(fn(Get $get) => $get('routing2') === true),
                                        Toggle::make('routing3')
                                            ->label('Methods')
                                            ->reactive()
                                            ->default(false),
                                        Select::make('methods')
                                            ->label('Methods')
                                            ->options([
                                                'GET' => 'GET',
                                                'PATCH' => 'PATCH',
                                                'HEAD' => 'HEAD',
                                                'PUT' => 'PUT',
                                                'DELETE' => 'DELETE',
                                                'CONNECT' => 'CONNECT',
                                                'POST' => 'POST',
                                                'OPTIONS' => 'OPTIONS',
                                                'TRACE' => 'TRACE',
                                            ])
                                            ->multiple()
                                            ->placeholder('Select one or more methods')
                                            ->visible(fn(Get $get) => $get('routing3') === true),
                                        Toggle::make('routing4')
                                            ->label('Headers')
                                            ->reactive()
                                            ->default(false),
                                        Repeater::make('headers')
                                            ->label('Headers')
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
                                            ->visible(fn(Get $get) => $get('routing4') === true),
                                        Toggle::make('routing5')
                                            ->label('SNIs')
                                            ->reactive()
                                            ->default(false),
                                        Repeater::make('snis')
                                            ->simple(
                                                TextInput::make('snis')
                                            )
                                            ->label('SNIs')
                                            ->addActionLabel('Add SNIs')
                                            ->visible(fn(Get $get) => $get('routing5') === true),
                                    ]),
                                Tabs\Tab::make('Expressions')
                                    ->schema([
                                        RichEditor::make('code')
                                            ->label('Routing Rules')
                                            ->toolbarButtons([''])
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
                    ->label('Name')
                    ->weight(FontWeight::Bold),
                TextColumn::make('protocol')
                    ->label('Protocols')
                    ->badge()
                    ->color('gray')
                    ->separator(','),
                TextColumn::make('hosts')
                    ->label('Hosts')
                    ->badge()
                    ->color('gray')
                    ->separator(','),
                TextColumn::make('methods')
                    ->label('Methods')
                    ->badge()
                    ->separator(','),
                TextColumn::make('paths')
                    ->label('Paths')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('expression')
                    ->label('Expression'),
                TextColumn::make('tags')
                    ->label('Tags')
                    ->badge()
                    ->separator(','),
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
