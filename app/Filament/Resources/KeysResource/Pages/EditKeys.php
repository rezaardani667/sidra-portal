<?php

namespace App\Filament\Resources\KeysResource\Pages;

use App\Filament\Resources\KeysResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKeys extends EditRecord
{
    protected static string $resource = KeysResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
