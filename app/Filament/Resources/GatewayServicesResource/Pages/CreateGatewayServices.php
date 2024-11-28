<?php

namespace App\Filament\Resources\GatewayServicesResource\Pages;

use App\Filament\Resources\GatewayServicesResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\ActionSize;

class CreateGatewayServices extends CreateRecord
{
    protected static string $resource = GatewayServicesResource::class;
    protected static ?string $title = 'New Gateway Service';
    protected ?string $subheading = 'Service entities are abstractions of each of your own upstream services.';

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
            $this->getCancelFormAction(),
            Action::make('Config')
            ->label('View Configuration')
            ->link(),
        ];
    }
}
