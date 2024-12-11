<?php

namespace App\Filament\Resources\RoutesResource\Pages;

use App\Filament\Resources\RoutesResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateRoutes extends CreateRecord
{
    protected static string $resource = RoutesResource::class;

    protected static ?string $title = 'Add a Route';
    protected ?string $subheading = 'A Route defines rules to match client requests, and is associated with a Gateway Service.';

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

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['headers'])) {
            $formattedHeaders = [];

            foreach ($data['headers'] as $header) {
                $formattedHeaders[] = [
                    $header['name'] => [$header['value']]
                ];
            }

            $data['headers'] = $formattedHeaders;
        }

        return $data;
    }
}
