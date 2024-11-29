<?php

namespace App\Filament\Resources\ConsumersResource\Pages;

use App\Filament\Resources\ConsumersResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateConsumers extends CreateRecord
{
    protected static string $resource = ConsumersResource::class;
    protected static ?string $title = 'New Consumer';
    protected ?string $subheading = 'A Consumer represents a User of a Service.';

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
