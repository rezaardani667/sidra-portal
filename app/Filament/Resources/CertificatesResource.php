<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CertificatesResource\Pages;
use App\Filament\Resources\CertificatesResource\RelationManagers;
use App\Models\Certificates;
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

use function Laravel\Prompts\textarea;

class CertificatesResource extends Resource
{
    protected static ?string $model = Certificates::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Gateway Manager';
    protected static ?int $navigationSort =  8;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Section::make('New Certificate')
                ->description('Certificates handle SSL/TLS termination for encrypted requests.')
                ->schema([
                    Section::make('SSL Key Pair')
                        ->description('The PEM-encoded public certificate chain and private
                                        key of the SSL key pair and the alternate.')
                        ->schema([
                            Textarea::make('cert')
                        ->label('Cert')
                        ->required()
                        ->rows(6),

            Textarea::make('key')
                ->label('Key')
                ->required()
                ->rows(6),

            Textarea::make('cert_alt')
                ->label('Cert Alt')
                ->rows(6),

            Textarea::make('key_alt')
                ->label('Key Alt')
                ->rows(6),

                ]),

            Section::make('General Information')
                ->description('General information will help identify and manage this key.')
                ->schema([
                    TextInput::make('tags')
                        ->label('Tags')
                        ->placeholder('Enter a list of tags separated by comma')
                        ->helperText('e.g. tag1, tag2, tag3')
                ])
                ]),


        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('cert')
                    ->label('Cert'),
                TextColumn::make('key')
                    ->label('Key'),
                TextColumn::make('cert_alt')
                    ->label('Cert Alt'),
                TextColumn::make('key_alt')
                    ->label('Key Alt'),
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
            'index' => Pages\ListCertificates::route('/'),
            'create' => Pages\CreateCertificates::route('/create'),
            'edit' => Pages\EditCertificates::route('/{record}/edit'),
        ];
    }
}
