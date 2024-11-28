<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UpstreamsResource\Pages;
use App\Filament\Resources\UpstreamsResource\RelationManagers;
use App\Models\Upstreams;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
    protected static ?string $model = Upstreams::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-up';
    protected static ?string $navigationGroup = 'Gateway Manager';
    protected static ?int $navigationSort =  7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('General Information')
                    ->description('General information will help identify and manage added consumer.')
                    ->schema([]),
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
                    ->helperText('Accepts an integer in the range of 10 - 65536')
                    ->schema([
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
                                Section::make('Hash Fallback')
                                ->description('What to use as hashing input if the primary hash_on does not return a hash')
                                ->schema([
                                    Select::make('hash_fallback')
                                        ->label('Hash Fallback')
                                        ->disabled()
                                ])
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
                    ->label('Hash Fallback')
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
