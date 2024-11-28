<?php

namespace App\Filament\Resources\GatewayServicesResource\Pages;

use App\Filament\Resources\GatewayServicesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGatewayServices extends EditRecord
{
    protected static string $resource = GatewayServicesResource::class;
    protected static ?string $title = 'New Gateway Service';
    protected ?string $subheading = 'Service entities are abstractions of each of your own upstream services.';

    protected function getHeaderActions(): array
    {
        return [];
    }
}
