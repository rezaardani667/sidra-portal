<?php

namespace App\Filament\Resources\GatewayServicesResource\Pages;

use App\Filament\Resources\GatewayServicesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGatewayServices extends ListRecords
{
    protected static string $resource = GatewayServicesResource::class;
    protected static ?string $title = 'Gateway Service';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label("New Gateway Service"),
        ];
    }
}
