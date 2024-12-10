<?php

namespace App\Filament\Resources\PluginsResource\Pages;

use App\Filament\Resources\PluginsResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreatePlugins extends CreateRecord
{
    protected static string $resource = PluginsResource::class;
    protected static ?string $title = 'Select a Plugin';
    protected ?string $subheading = 'Choose a plugin from our catalog to install for your organization.';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
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

    protected function afterCreate(): void
    {
        PluginsResource::setAppliedTo($this->getRecord());
    }
}
