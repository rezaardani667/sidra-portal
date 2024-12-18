<?php

namespace App\Filament\Resources\DataPlaneNodesResource\Pages;

use App\Filament\Resources\DataPlaneNodesResource;
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
    protected static ?string $title = 'Create a Gateway';
    protected static string $view = 'filament.pages.create-record';

    protected function afterCreate(): void
    {
        $this->dispatch('open-modal', id: 'register-action');
    }

    protected function getCreateFormAction(): Action
    {
        return Action::make('create')
            ->label(__('filament-panels::resources/pages/create-record.form.actions.create.label'))
            ->action(function(){$this->dispatch('open-modal', id: 'register-action');}
            )
            ->keyBindings(['mod+s']);
    }

    public function create(bool $another = false): void
    {
        $this->dispatch('open-modal', id: 'register-action');
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
            // Ensure that the form record is anonymized so that relationships aren't loaded.
            $this->form->model($this->getRecord()::class);
            $this->record = null;

            $this->fillForm();

            return;
        }

        // $redirectUrl = $this->getRedirectUrl();

        // $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode() && is_app_url($redirectUrl));
    }

    protected function getViewData(): array
    {
        return [
            'action' => $this->modal()
            ];
    }

    private function modal() : Action
    {
        return Action::make('register')
            ->modalAlignment(Alignment::Left)
            ->modalFooterActionsAlignment(Alignment::Center)
            ->modalCloseButton(true)
            ->closeModalByClickingAway(false)
            ->closeModalByEscaping(false)
            ->modalHeading('Installing Helm Chart')
            ->modalDescription('Here are the steps to install a Sidra via Helm chart:')
            
            ->modalCancelAction(false);
    }
}
