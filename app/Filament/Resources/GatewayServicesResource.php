<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GatewayServicesResource\Pages;
use App\Filament\Resources\GatewayServicesResource\RelationManagers;
use App\Models\GatewayServices;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GatewayServicesResource extends Resource
{
    protected static ?string $model = GatewayServices::class;
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationGroup = 'Gateway Manager';
    protected static ?int $navigationSort = 2;


    public static function form(Form $form): Form
    {
        return $form
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
                Radio::make('traffic_option')
                    ->label('Choose how and where to send traffic')
                    ->options([
                        'Full URL' => 'Full URL',
                    ])
                    ->required()
                    ->schema([
                        TextInput::make('upstream_url')
                            ->label('Upstream URL')
                            ->placeholder('Enter a URL')
                            ->hidden(fn (callable $get) => $get('traffic_option') !== 'upstream_url')
                            ->required()
                            ->helperText('Protocol,Host,Port and Path')
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name'),
                TextColumn::make('tags')
                    ->label('Tags'),
                TextColumn::make('traffic_options')
                    ->label('Traffic Options'),
                TextColumn::make('upstream_url')
                    ->label('Upstream URL')
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
