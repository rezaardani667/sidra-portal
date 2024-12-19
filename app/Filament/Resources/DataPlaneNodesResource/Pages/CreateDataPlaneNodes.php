<?php

namespace App\Filament\Resources\DataPlaneNodesResource\Pages;

use App\Filament\Resources\DataPlaneNodesResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\StaticAction;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Alignment;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;
use Throwable;

class CreateDataPlaneNodes extends CreateRecord
{
    protected static string $resource = DataPlaneNodesResource::class;
    protected static ?string $title = 'Create a Data Plane';

    protected static string $view = 'filament.pages.create-record';

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function afterCreate(): void
    {
        $this->dispatch('open-modal', id: 'dataplane-install');
    }

    public function create(bool $another = false): void
    {
        $this->authorizeAccess();
        try {
            $this->beginDatabaseTransaction();
            $this->callHook('beforeValidate');
            $data = $this->form->getState();
            $this->callHook('afterValidate');
            $data = $this->mutateFormDataBeforeCreate($data);
            $this->callHook('beforeCreate');
            $this->record = $this->handleRecordCreation($data);
            $this->form->model($this->getRecord())->saveRelationships();
            $this->callHook('afterCreate');
            $this->commitDatabaseTransaction();
        } catch (Halt $exception) {
            $exception->shouldRollbackDatabaseTransaction() ?
            $this->rollBackDatabaseTransaction() :
            $this->commitDatabaseTransaction();
            return;
        } catch (Throwable $exception) {
            $this->rollBackDatabaseTransaction();
            throw $exception;
        }
        $this->rememberData();
        $this->getCreatedNotification()?->send();
        if ($another) {
            $this->form->model($this->getRecord()::class);
            $this->record = null;
            $this->fillForm();
            return;
        }
    }

    protected function getViewData(): array
    {
        return [
            'deployment_models' => $this->record->deployment_models ?? 'kubernetes',
            'dataplane_id' => $this->record->id ?? null,
            'action' => $this->modal(),
        ];
    } 
    
    private function modal() : Action
    {
        return Action::make('close')
            ->modalAlignment(Alignment::Left)
            ->modalFooterActionsAlignment(Alignment::Center)
            ->modalCloseButton(false)
            ->closeModalByClickingAway(false)
            ->closeModalByEscaping(false)
            ->modalHeading('How to install Sidra')
            ->modalSubmitAction(StaticAction::make('join-group')
                ->label('Close')
                ->button()
                ->openUrlInNewTab(true)
                ->url(fn (): string => $this->record ? DataPlaneNodesResource::getUrl('view', ['record' => $this->record->id]) : '#')
            )
            ->modalCancelAction(false);
    }

}
