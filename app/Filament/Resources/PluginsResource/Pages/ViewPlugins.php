<?php

namespace App\Filament\Resources\PluginsResource\Pages;

use App\Filament\Resources\PluginsResource;
use Faker\Provider\ar_EG\Text;
use Filament\Actions;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;

class ViewPlugins extends ViewRecord
{
    protected static string $resource = PluginsResource::class;

    public function mount(int | string $record): void
    {
        parent::mount($record);
        static::$title = $this->getRecord()->name;
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()
                    ->schema([
                        TextEntry::make('About this Plugin')
                            ->weight(FontWeight::Bold)
                            ->size(TextEntry\TextEntrySize::Large)
                            ->columnSpanFull(),
                        TextEntry::make('id')
                            ->label('ID')
                            ->badge()
                            ->icon('heroicon-m-document-duplicate')
                            ->iconPosition(IconPosition::After)
                            ->copyable(),
                        TextEntry::make('type_plugin')
                            ->label('Type Plugin')
                            ->badge()
                            ->color('gray'),
                        TextEntry::make('ordering')
                            ->label('Ordering')
                    ])
                    ->columns(3),
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Configuration')
                            ->schema([
                                TextEntry::make('id')
                                    ->label('ID')
                                    ->inlineLabel()
                                    ->icon('heroicon-m-document-duplicate')
                                    ->iconPosition(IconPosition::After)
                                    ->copyable(),
                                TextEntry::make('type_plugin')
                                    ->label('Name')
                                    ->inlineLabel(),
                                TextEntry::make('enabled')
                                    ->label('Enabled')
                                    ->formatStateUsing(fn(string $state): string => $state === '1' ? 'Enabled' : 'Disabled')
                                    ->color(fn(string $state): string => match ($state) {
                                        '1' => 'success',
                                        '0' => 'danger',
                                    })
                                    ->badge()
                                    ->inlineLabel(),
                                TextEntry::make('updated_at')
                                    ->label('Last Updated')
                                    ->inlineLabel(),
                                TextEntry::make('created_at')
                                    ->label('Created')
                                    ->inlineLabel(),
                                TextEntry::make('protocols')
                                    ->label('Protocols')
                                    ->inlineLabel()
                                    ->badge(),
                                TextEntry::make(''),
                                TextEntry::make('Plugin Specific Configuration'),
                                TextEntry::make('credentials')
                                    ->label('Hide Credentials')
                                    ->badge()
                                    ->formatStateUsing(fn(string $state): string => $state === '1' ? 'Enabled' : 'Disabled')
                                    ->color(fn(string $state): string => match ($state) {
                                        '1' => 'success',
                                        '0' => 'danger',
                                    })
                                    ->inlineLabel()
                                    ->color('gray'),
                                TextEntry::make('realm')
                                    ->label('Realm')
                                    ->inlineLabel(),
                                TextEntry::make('anonymous')
                                    ->label('Anonymous')
                                    ->inlineLabel()
                            ]),
                        Tabs\Tab::make('Ordering')
                            ->schema([
                                // ...
                            ]),
                    ])->columnSpanFull()
            ]);
    }
}
