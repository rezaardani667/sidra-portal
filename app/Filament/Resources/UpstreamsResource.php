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
    protected static ?string $navigationIcon = 'heroicon-o-cloud-arrow-up';
    protected static ?string $navigationGroup = 'Gateway Manager';
    protected static ?int $navigationSort =  7;

    public static function form(Form $form): Form
    {
        //@todo: name, upstream host, upstream port dan model migration disesuaikan
        // (Load balancing disimpan untuk back up)
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
                            ->placeholder('Enter or select a Certificate ID'),

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
            ])
            ->emptyStateHeading('Configure a New Upstream')
            ->emptyStateDescription('Upstreams are used to load balance incoming requests.')
            ->emptyStateIcon('heroicon-o-cloud-arrow-up')
            ->emptyStateActions([
                Tables\Actions\Action::make('create')
                    ->url(fn(): string => UpstreamsResource::getUrl('create'))
                    ->label('New Upstream'),
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
