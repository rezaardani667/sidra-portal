<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GatewayServicesResource\Pages;
use App\Filament\Resources\GatewayServicesResource\RelationManagers;
use App\Models\GatewayServices;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GatewayServicesResource extends Resource
{
    protected static ?string $model = Service::class;

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
                        TextInput::make('name')
                            ->label('Name')
                            ->placeholder('Enter a unique name')
                            ->required(),
                        TextInput::make('tags')
                            ->label('Tags')
                            ->placeholder('Enter a list of tags separated by comma')
                            ->helperText('e.g. tag1, tag2, tag3')
                            ->required(),
                    ]),
                Section::make('Service Endpoint')
                    ->description('Define the endpoint for this service by specifying the full URL or by its separate elements.')
                    ->aside()
                    ->schema([
                        Radio::make('traffic_option')
                            ->label('Choose how and where to send traffic')
                            ->options([
                                'Full URL' => 'Full URL',
                                'Protocol,Host,Port and Path' => 'Protocol,Host,Port and Path',
                            ])
                            ->required()
                            ->schema([
                                TextInput::make('upstream_url')
                                    ->label('Upstream URL')
                                    ->placeholder('Enter a URL')
                                    ->hidden(fn(callable $get) => $get('traffic_option') !== 'upstream_url')
                                    ->required()
                                    ->helperText('Protocol, Host, Port and Path')
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name'),
                TextColumn::make('protocol')
                    ->label('Protocol'),
                TextColumn::make('host')
                    ->label('Host'),
                TextColumn::make('port')
                    ->label('Port'),
                TextColumn::make('path')
                    ->label('Path'),
                ToggleColumn::make('enabled')
                    ->label('Enabled'),
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
            ->emptyStateHeading('Configure a New Gateway Service')
            ->emptyStateDescription('Gateway services are used to proxy traffic.')
            ->emptyStateIcon('heroicon-o-server-stack')
            ->emptyStateActions([
                Tables\Actions\Action::make('create')
                    ->url(fn (): string => GatewayServicesResource::getUrl('create'))
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
            'edit' => Pages\EditGatewayServices::route('/{record}/edit'),
        ];
    }
}
