<?php

declare(strict_types=1);

namespace Cortex\Actions;

use Cortex\Support\Icons\Heroicon;
use Cortex\Support\Facades\CortexIcon;
use Illuminate\Database\Eloquent\Model;
use Cortex\Actions\Concerns\CanCustomizeProcess;

class DeleteAction extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'delete';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('cortex.actions::delete.single.label'));

        $this->modalHeading(fn (): string => __('cortex.actions::delete.single.modal.heading', ['label' => $this->getRecordTitle()]));

        $this->modalSubmitActionLabel(__('cortex.actions::delete.single.modal.actions.delete.label'));

        $this->successNotificationTitle(__('cortex.actions::delete.single.notifications.deleted.title'));

        $this->color('danger');

        $this->tableIcon(CortexIcon::resolve('actions::delete-action') ?? Heroicon::Trash);
        $this->groupedIcon(CortexIcon::resolve('actions::delete-action.grouped') ?? Heroicon::Trash);

        $this->requiresConfirmation();

        $this->modalIcon(CortexIcon::resolve('actions::delete-action.modal') ?? Heroicon::OutlinedTrash);

        $this->keyBindings(['mod+d']);

        $this->hidden(static function (Model $record): bool {
            if (! method_exists($record, 'trashed')) {
                return false;
            }

            return $record->trashed();
        });

        $this->action(function (): void {
            $result = $this->process(static fn (Model $record) => $record->delete());

            if (! $result) {
                $this->failure();

                return;
            }

            $this->success();
        });
    }
}
