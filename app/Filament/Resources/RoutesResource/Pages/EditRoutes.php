<?php

namespace App\Filament\Resources\RoutesResource\Pages;

use App\Filament\Resources\RoutesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditRoutes extends EditRecord
{
    protected static string $resource = RoutesResource::class;

    protected static ?string $title = 'Edit Route';
    protected ?string $subheading = 'A Route defines rules to match client requests, and is associated with a Gateway Service.';

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['headers'])) {
            $formattedHeaders = [];
        
            foreach ($data['headers'] as $header) {
                if (isset($header['name'], $header['value'])) {
                    $formattedHeaders[$header['name']][] = $header['value'];
                }
            }
        
            $data['headers'] = $formattedHeaders;
        }
        
        return $data;
    }

    protected function fillFormWithDataAndCallHooks(Model $record, array $extraData = []): void
    {
        $this->callHook('beforeFill');

        $data = $this->mutateFormDataBeforeFill([
            ...$record->attributesToArray(),
            ...$extraData,
        ]);

        $formated = [];
        foreach ( $data['headers'] as $key => $value) {
            $formated[] = ['name' => $key, 'value' => implode(',', $value)];
        }
        $data['headers'] = $formated;
        $data['routing1'] = !empty($data['paths']) ? true : false ;
        $data['routing2'] = !empty($data['hosts']) ? true : false ;
        $data['routing3'] = !empty($data['methods']) ? true : false ;
        $data['routing4'] = !empty($data['headers']) ? true : false ;
        $data['routing5'] = !empty($data['snis']) ? true : false ;

        $this->form->fill($data);

        $this->callHook('afterFill');

    }
    
}
