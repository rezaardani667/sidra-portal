<?php

namespace App\Filament\Resources\SNIsResource\Pages;

use App\Filament\Resources\SNIsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSNIs extends EditRecord
{
    protected static string $resource = SNIsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
