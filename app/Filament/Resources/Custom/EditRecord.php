<?php

namespace App\Filament\Resources\Custom;

use Filament\Resources\Pages\EditRecord as BaseEditRecord;

class EditRecord extends BaseEditRecord
{
    protected function getRedirectUrl(): ?string
    {
        return null;
    }
}