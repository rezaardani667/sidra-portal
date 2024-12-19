<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConsumersResource\Pages;
use App\Filament\Resources\ConsumersResource\RelationManagers;
use App\Models\Consumer;
use App\Models\Consumers;
use Filament\Forms;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConsumersResource extends Resource
{
    protected static ?string $model = Consumer::class;

    protected static ?int $navigationSort =  5;

    protected static ?string $navigationGroup = 'Gateway Manager';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('General Information')
                    ->description('General information will help identify and manage added consumer.')
                    ->aside()
                    ->schema([
                        Section::make('Consumer Identification')
                            ->description('A consumer can have both unique username and unique custom ID or one of them.')
                            ->schema([
                                TextInput::make('username')
                                    ->label('Username')
                                    ->placeholder('Enter a unique username')
                                    ->required(),
                                TextInput::make('custom_id')
                                    ->label('Custom ID')
                                    ->placeholder('Enter a unique custom ID')
                                    ->required()
                            ]),
                        TextInput::make('tags')
                            ->label('Tags')
                            ->placeholder('Enter a list of tags separated by comma')
                            ->helperText('e.g. tag1, tag2, tag3'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('username')
                    ->label('Username')
                    ->weight(FontWeight::Bold),
                TextColumn::make('custom_id')
                    ->label('Custom ID'),
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
            ])
            ->emptyStateHeading('Configure a New Consumer')
            ->emptyStateDescription('Consumers are the end users of a service.')
            ->emptyStateIcon('heroicon-o-user-group')
            ->emptyStateActions([
                Tables\Actions\Action::make('create')
                    ->url(fn(): string => ConsumersResource::getUrl('create'))
                    ->label('New Consumer'),
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
            'index' => Pages\ListConsumers::route('/'),
            'create' => Pages\CreateConsumers::route('/create'),
            'edit' => Pages\EditConsumers::route('/{record}/edit'),
        ];
    }
}
