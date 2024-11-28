<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KeysResource\Pages;
use App\Filament\Resources\KeysResource\RelationManagers;
use App\Models\Keys;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KeysResource extends Resource
{
    protected static ?string $model = Keys::class;
    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?string $navigationGroup = 'Gateway Manager';
    protected static ?int $navigationSort =  11;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('General Information')
                    ->description('General information will help identify and manage this key set.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->placeholder('enter a unique name for this key set'),

                        TextInput::make('tags')
                            ->label('Tags')
                            ->placeholder('Enter a list of tags separated by comma')
                            ->helperText('e.g. tag1, tag2, tag3')
                            ->required(),
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
                    ->label('Tags')
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
            'index' => Pages\ListKeys::route('/'),
            'create' => Pages\CreateKeys::route('/create'),
            'edit' => Pages\EditKeys::route('/{record}/edit'),
        ];
    }
}
