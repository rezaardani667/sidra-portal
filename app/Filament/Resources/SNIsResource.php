<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SNIsResource\Pages;
use App\Filament\Resources\SNIsResource\RelationManagers;
use App\Models\SNIs;
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

use function Laravel\Prompts\select;

class SNIsResource extends Resource
{
    protected static ?string $model = SNIs::class;
    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';
    protected static ?string $navigationGroup = 'Gateway Manager';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('General Information')
                ->description('General information will help identify and manage this SNI.')
                ->schema([
                    TextInput::make('name')
                        ->label('Name')
                        ->placeholder('Enter a unique name for this SNI')
                        ->required(),

                    TextInput::make('tags')
                        ->label('Tags')
                        ->placeholder('Enter a list of tags separated by comma')
                        ->helperText('e.g. tag1, tag2, tag3')
                        ->required(),
                ]),
                Section::make('Certificate')
                        ->description('Map an existing Certificate object to hostnames')
                        ->schema([
                            Select::make('ssl_certificate')
                                ->label('SSL Certificated ID')
                                ->placeholder('Enter or select a Certificated ID')
                        ])
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
                TextColumn::make('ssl_certificate')
                    ->label('SSL Certificated ID')
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
            'index' => Pages\ListSNIs::route('/'),
            'create' => Pages\CreateSNIs::route('/create'),
            'edit' => Pages\EditSNIs::route('/{record}/edit'),
        ];
    }
}
