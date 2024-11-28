<?php

namespace App\Filament\Resources\SNIsResource\Pages;

use App\Filament\Resources\SNIsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSNIs extends ListRecords
{
    protected static string $resource = SNIsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
