<?php

namespace App\Filament\Resources\DataPlaneNodesResource\Pages;

use App\Filament\Resources\DataPlaneNodesResource;
use Filament\Actions;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;

class ViewDataPlaneNodes extends ViewRecord
{
    protected static string $resource = DataPlaneNodesResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('About This Serverless Gateway')
                    ->description('This is your serverless gateway control plane. It is the easiest way to get started with the Konnect Gateway.')
                    ->schema([
                        TextEntry::make('id')
                            ->label('ID:')
                            ->badge()
                            ->icon('heroicon-m-document-duplicate')
                            ->iconPosition(IconPosition::After)
                            ->copyable(),
                        TextEntry::make('status')
                            ->label('Status:')
                            ->badge()
                            ->colors([
                                'primary' => 'active',
                                'danger' => 'inactive',
                            ]),
                        TextEntry::make('created_at')
                            ->label('Created:')
                            ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->timezone('Asia/Jakarta')->format('M j, Y, g:i A')),
                    ])
                    ->columns(3),
            ]);
    }
}
