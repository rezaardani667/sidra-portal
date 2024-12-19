<?php

namespace App\Filament\Resources\RoutesResource\Pages;

use App\Filament\Resources\RoutesResource;
use Faker\Provider\ar_EG\Text;
use Filament\Actions;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;

class ViewRoutes extends ViewRecord
{
    protected static string $resource = RoutesResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make(fn() => $this->getRecord()->name)
                    ->description('About this Route')
                    ->schema([
                        TextEntry::make('id')
                            ->label('ID')
                            ->badge()
                            ->icon('heroicon-m-document-duplicate')
                            ->iconPosition(IconPosition::After)
                            ->columnSpan(3)
                            ->copyable(),
                        TextEntry::make('tags')
                            ->label('Tags')
                            ->inlineLabel()
                            ->badge()
                            ->columns(1)
                            ->separator(','),
                        TextEntry::make('This Route is attached to the following Gateway Service(s):')
                            ->columnSpanFull(),
                        TextEntry::make('gatewayService.name')
                            ->label('')
                            ->icon('heroicon-m-square-2-stack')
                            ->columns(1)
                            ->badge(),
                        TextEntry::make('gatewayService.id')
                            ->label('Gateway Service ID')
                            ->badge()
                            ->inlineLabel()
                            ->icon('heroicon-m-document-duplicate')
                            ->iconPosition(IconPosition::After)
                            ->columnSpan(3)
                            ->copyable(),
                    ])
                    ->columns(6),
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make('Analytics')
                            ->schema([
                                //...
                            ]),
                        Tab::make('Plugins')
                            ->schema([
                                //...
                            ]),
                        Tab::make('Configuration')
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
                                TextEntry::make('updated_at')
                                    ->label('Last Updated')
                                    ->inlineLabel(),
                                TextEntry::make('created_at')
                                    ->label('Created')
                                    ->inlineLabel(),
                                TextEntry::make('gatewayService.name')
                                    ->label('Gateway Service')
                                    ->inlineLabel()
                                    ->badge(),
                                TextEntry::make('tags')
                                    ->label('Tags')
                                    ->inlineLabel()
                                    ->badge()
                                    ->separator(','),
                                TextEntry::make('paths')
                                    ->label('Paths')
                                    ->inlineLabel()
                                    ->badge()
                                    ->icon('heroicon-m-document-duplicate')
                                    ->iconPosition(IconPosition::After)
                                    ->copyable(),
                                TextEntry::make(''),
                                TextEntry::make('Advanced'),
                                TextEntry::make('https_redirect_status_code')
                                    ->label('Https Redirect Status Code')
                                    ->inlineLabel(),
                                TextEntry::make('regex_priority')
                                    ->label('Regex Priority')
                                    ->inlineLabel(),
                                TextEntry::make('strip_path')
                                    ->label('Strip Path')
                                    ->inlineLabel(),
                                TextEntry::make('preserve_host')
                                    ->label('Preserve Host')
                                    ->inlineLabel(),
                                TextEntry::make('request_buffering')
                                    ->label('Request Buffering')
                                    ->inlineLabel(),
                                TextEntry::make('response_buffering')
                                    ->label('Response Buffering')
                                    ->inlineLabel(),
                                TextEntry::make('path_handling')
                                    ->label('Path Handling')
                                    ->inlineLabel()
                            ])
                    ])->columnSpanFull()
            ]);
    }
}
