<?php

namespace App\Filament\Resources\UpstreamsResource\Pages;

use App\Filament\Resources\UpstreamsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUpstreams extends ListRecords
{
    protected static string $resource = UpstreamsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
