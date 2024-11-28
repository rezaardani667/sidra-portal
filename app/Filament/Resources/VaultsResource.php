<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VaultsResource\Pages;
use App\Filament\Resources\VaultsResource\RelationManagers;
use App\Models\Vaults;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VaultsResource extends Resource
{
    protected static ?string $model = Vaults::class;
    protected static ?string $navigationIcon = 'heroicon-o-square-3-stack-3d';
    protected static ?string $navigationGroup = 'Gateway Manager';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('General Information')
                    ->description('General information helps identify and manage this Vault instance.')
                    ->schema([
                        TextInput::make('prefix')
                            ->label('Prefix')
                            ->placeholder('Enter a unique prefix for this vault')
                            ->helperText('e.g.my-vault'),

                        Textarea::make('description')
                            ->label('Description')
                            ->placeholder('Enter some description for this vault'),

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
                TextColumn::make('prefix')
                    ->label('Prefix'),
                TextColumn::make('description')
                    ->label('Description'),
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
            'index' => Pages\ListVaults::route('/'),
            'create' => Pages\CreateVaults::route('/create'),
            'edit' => Pages\EditVaults::route('/{record}/edit'),
        ];
    }
}
