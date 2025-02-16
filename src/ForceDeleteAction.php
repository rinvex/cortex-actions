<?php

declare(strict_types=1);

namespace Cortex\Actions;

use Cortex\Support\Icons\Heroicon;
use Cortex\Support\Facades\CortexIcon;
use Illuminate\Database\Eloquent\Model;
use Cortex\Actions\Concerns\CanCustomizeProcess;

class ForceDeleteAction extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'forceDelete';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('cortex.actions::force-delete.single.label'));

        $this->modalHeading(fn (): string => __('cortex.actions::force-delete.single.modal.heading', ['label' => $this->getRecordTitle()]));

        $this->modalSubmitActionLabel(__('cortex.actions::force-delete.single.modal.actions.delete.label'));

        $this->color('danger');

        $this->tableIcon(CortexIcon::resolve('actions::force-delete-action') ?? Heroicon::Trash);
        $this->groupedIcon(CortexIcon::resolve('actions::force-delete-action.grouped') ?? Heroicon::Trash);

        $this->requiresConfirmation();

        $this->modalIcon(CortexIcon::resolve('actions::force-delete-action.modal') ?? Heroicon::OutlinedTrash);

        $this->action(function (): void {
            $result = $this->process(static fn (Model $record) => $record->forceDelete());

            if (! $result) {
                $this->failure();

                return;
            }

            $this->success();
        });

        $this->visible(static function (Model $record): bool {
            if (! method_exists($record, 'trashed')) {
                return false;
            }

            return $record->trashed();
        });
    }
}
