<?php

namespace App\Filament\Resources\GatewayServicesResource\Pages;

use App\Filament\Resources\GatewayServicesResource;
use App\Filament\Widgets\TableRoutes;
use Faker\Provider\ar_EG\Text;
use Filament\Actions;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;

class ViewGatewayServices extends ViewRecord
{
    protected static string $resource = GatewayServicesResource::class;
    protected static string $view = 'filament.pages.view-record';

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make(fn() => $this->getRecord()->name)
                    ->description('About this Gateway Service')
                    ->schema([
                        TextEntry::make('id')
                            ->label('ID')
                            ->badge()
                            ->icon('heroicon-m-document-duplicate')
                            ->iconPosition(IconPosition::After)
                            ->copyable(),
                        TextEntry::make('host')
                            ->label('Upstream URL')
                            ->badge()
                            ->icon('heroicon-m-document-duplicate')
                            ->iconPosition(IconPosition::After)
                            ->copyable(),
                        TextEntry::make('tags')
                            ->label('Tags')
                            ->badge()
                            ->color('gray')
                            ->separator(','),
                    ])
                    ->columns(3),
                Section::make('Configuration')
                    ->schema([
                        TextEntry::make('id')
                            ->label('ID')
                            ->inlineLabel()
                            ->icon('heroicon-m-document-duplicate')
                            ->iconPosition(IconPosition::After)
                            ->copyable(),
                        TextEntry::make('name')
                            ->label('Name')
                            ->inlineLabel(),
                        TextEntry::make('enabled')
                            ->label('Enabled')
                            ->badge()
                            ->formatStateUsing(fn(string $state): string => $state === '1' ? 'Enabled' : 'Disabled')
                            ->color(fn(string $state): string => match ($state) {
                                '1' => 'success',
                                '0' => 'danger',
                            })
                            ->inlineLabel(),
                        TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->inlineLabel(),
                        TextEntry::make('created_at')
                            ->label('Created')
                            ->inlineLabel(),
                        TextEntry::make('host')
                            ->label('Host')
                            ->inlineLabel(),
                        TextEntry::make('protocol')
                            ->label('Protocol')
                            ->inlineLabel(),
                        TextEntry::make('tags')
                            ->label('Tags')
                            ->inlineLabel()
                            ->badge()
                            ->separator(','),
                    ])
                    ->columnSpan(1)
                    ->collapsible(),
                Section::make('Advanced')
                    ->schema([
                        TextEntry::make('retries')
                            ->label('Retries')
                            ->inlineLabel(),
                        TextEntry::make('connect_timeout')
                            ->label('Connect Timeout')
                            ->inlineLabel(),
                        TextEntry::make('write_timeout')
                            ->label('Write Timeout')
                            ->inlineLabel(),
                        TextEntry::make('read_timeout')
                            ->label('Read Timeout')
                            ->inlineLabel(),
                    ])
                    ->columnSpan(1)
                    ->collapsible(),
            ]);
    }

    protected function getFooterWidgets(): array
    {
        return [
            TableRoutes::class,
        ];
    }
}
