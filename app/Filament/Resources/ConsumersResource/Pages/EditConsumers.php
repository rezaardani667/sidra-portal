<?php

namespace App\Filament\Resources\ConsumersResource\Pages;

use App\Filament\Resources\ConsumersResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConsumers extends EditRecord
{
    protected static string $resource = ConsumersResource::class;
    protected static ?string $title = 'New Consumer';
    protected ?string $subheading = 'A Consumer represents a User of a Service.';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
