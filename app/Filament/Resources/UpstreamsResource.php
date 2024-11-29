<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UpstreamsResource\Pages;
use App\Filament\Resources\UpstreamsResource\RelationManagers;
use App\Models\Upstream;
use App\Models\Upstreams;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PhpParser\Node\Stmt\Label;

use function Laravel\Prompts\select;

class UpstreamsResource extends Resource
{
    protected static ?string $model = Upstream::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-up';
    protected static ?string $navigationGroup = 'Gateway Manager';
    protected static ?int $navigationSort =  7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('General Information')
                    ->description('General information will help identify and manage added consumer.')
                    ->aside()
                    ->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->placeholder('Enter or select a host')
                    ->required(),

                TextInput::make('host_header')
                    ->label('Host Header')
                    ->required(),

                TextInput::make('client_certificate')
                    ->label('Client Certificate')
                    ->placeholder('Enter or select a Certificate ID')
                    ->required(),

                TextInput::make('tags')
                    ->label('Tags')
                    ->placeholder('Enter a list of tags separated by comma')
                    ->helperText('e.g. tag1, tag2, tag3')
                    ->required(),
            ]),
                Section::make('Load Balancing')
                    ->description('Active health checks actively probe targets for their health. Currently only support HTTP/HTTPS targets.')
                    ->aside()
                    ->schema([
                Select::make('algorithm')
                    ->label('Algorithm')
                    ->options([
                        'Round Robin' => 'Round Robin',
                        'Least Connections' => 'Least Connections',
                        'Consistent Hashing' => 'Consistent Hashing',
                        'Latency' => 'Latency',
                    ])
                    ->required(),

                TextInput::make('slots')
                    ->label('Slots')
                    ->helperText('Accepts an integer in the range of 10 - 65536'),

            Section::make('Hash on')
                ->description('What to use as hashing input.')
                ->schema([
                    Select::make('Hash_on')
                        ->label('Hash on')
                        ->required()
                        ->options([
                            'None' => 'None',
                            'Consumer' => 'Consumer',
                            'IP' => 'IP',
                            'Header' => 'Header',
                            'Cookie' => 'Cookie',
                            'Path' => 'Path',
                            'Query Argment' => 'Query Argument',
                            'URI Capture' => 'URI Capture',
                        ]),
                    ]),
                    Section::make('Hash Fallback')
                    ->description('What to use as hashing input if the primary hash_on does not return a hash')
                    ->schema([
                        Select::make('hash_fallback')
                            ->label('Hash Fallback')
                            ->disabled()
            ])
                    ]),
                    Section::make('Health Checks & Circuit Breakers')
                        ->description('Active health checks actively probe targets for their health. Currently only support HTTP/HTTPS targets.')
                        ->aside()
                        ->schema([
                            Section::make('Active Health Checks')
                                ->description('Actively probe the targets for their health.')
                                ->schema([
                                Toggle::make('health_check')
                                    ->label('')
                                ]),
                            Section::make('Passive Health Checks / Circuit Breakers')
                                ->description('Checks performed based on the requests being proxied by Kong (HTTP/HTTPS/TCP), with no additional traffic being generated.')
                                ->schema([
                                    Toggle::make('circuit_breakers')
                                        ->label('')
                                ]),
                            TextInput::make('healthchecks_threshold')
                                ->label('Healthchecks Threshold')
                                ->numeric()
                                ->placeholder('0')
                        ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name'),
                TextColumn::make('host_header')
                    ->label('Host Header'),
                TextColumn::make('client_certificate')
                    ->label('Client Certificate'),
                TextColumn::make('tags')
                    ->label('Tags'),
                TextColumn::make('algorithm')
                    ->label('Algorithm'),
                TextColumn::make('slots')
                    ->label('Slots'),
                TextColumn::make('hash_on')
                    ->label('Hash on'),
                TextColumn::make('hash_fallback')
                    ->label('Hash Fallback'),
                TextColumn::make('health_checks')
                    ->label('Active Health Checks'),
                TextColumn::make('healthchecks_threshold')
                    ->label('Healthchecks Threshold')
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
            'index' => Pages\ListUpstreams::route('/'),
            'create' => Pages\CreateUpstreams::route('/create'),
            'edit' => Pages\EditUpstreams::route('/{record}/edit'),
        ];
    }
}
