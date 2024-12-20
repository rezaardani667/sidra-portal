<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoutesResource\Pages;
use App\Filament\Resources\RoutesResource\RelationManagers;
use App\Models\Route;
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
                        TextInput::make('paths')
                            ->regex('/\//')
                            ->validationMessages([
                                'regex' => 'path - invalid path: must begin with `/` and should not include characters outside of the reserved list of RFC 3986'
                            ]),
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
                        Select::make('upstream_url')
                            ->label('Upstream Url')
                            ->options(
                                \App\Models\Upstream::all()->mapWithKeys(function ($upstream) {
                                    return ["{$upstream->upstream_host}:{$upstream->upstream_port}" => "{$upstream->upstream_host}:{$upstream->upstream_port}"];
                                })->toArray()
                            ),

                        Select::make('path_type')
                            ->label('Path Type')
                            ->options([
                                'prefix' => 'Prefix',
                                'fixed' => 'Fixed'
                            ]),
                        Tabs::make('Tabs')
                            ->tabs([
                                Tabs\Tab::make('Traditional')
                                    ->schema([
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
                                    ]),
                                Tabs\Tab::make('Expressions')
                                    ->schema([
                                        RichEditor::make('code')
                                            ->label('Routing Rules')
                                            ->toolbarButtons([''])
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
                                Select::make('https_redirect_status_code')
                                    ->label('HTTPS Redirect Status Code')
                                    ->default('426')
                                    ->options([
                                        '426' => '426',
                                        '301' => '301',
                                        '302' => '302',
                                        '307' => '307',
                                        '308' => '308',
                                    ]),
                                TextInput::make('regex_priority')
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
                            ->collapsed(),
                    ]),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('paths')
                    ->label('Paths')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('gatewayService.protocol')
                    ->label('Protocols')
                    ->badge()
                    ->color('gray')
                    ->separator(','),
                TextColumn::make('methods')
                    ->label('Methods')
                    ->badge()
                    ->separator(','),
                TextColumn::make('upstream_url')
                    ->label('Upstream URL'),
                TextColumn::make('expression')
                    ->label('Expression'),
                TextColumn::make('tags')
                    ->label('Tags')
                    ->badge()
                    ->searchable()
                    ->formatStateUsing(function ($state) {
                        if (!$state) {
                            return null;
                        }
                        $tags = explode(',', $state);
                        $firstTag = $tags[0];
                        $additionalCount = count($tags) - 1;
                        return $additionalCount > 0 ? "{$firstTag} {$additionalCount}+" : $firstTag;
                    }),
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
            'view' => Pages\ViewRoutes::route('/{record}'),
            'edit' => Pages\EditRoutes::route('/{record}/edit'),
        ];
    }
}
