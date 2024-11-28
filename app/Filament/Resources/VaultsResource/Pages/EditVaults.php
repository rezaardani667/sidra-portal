<?php

namespace App\Filament\Resources\VaultsResource\Pages;

use App\Filament\Resources\VaultsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVaults extends EditRecord
{
    protected static string $resource = VaultsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
