<?php

namespace App\Filament\Resources\PluginTypeResource\Pages;

use App\Filament\Resources\PluginTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPluginType extends EditRecord
{
    protected static string $resource = PluginTypeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
