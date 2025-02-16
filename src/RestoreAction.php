<?php

declare(strict_types=1);

namespace Cortex\Actions;

use Cortex\Support\Icons\Heroicon;
use Cortex\Support\Facades\CortexIcon;
use Illuminate\Database\Eloquent\Model;
use Cortex\Actions\Concerns\CanCustomizeProcess;

class RestoreAction extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'restore';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('cortex.actions::restore.single.label'));

        $this->modalHeading(fn (): string => __('cortex.actions::restore.single.modal.heading', ['label' => $this->getRecordTitle()]));

        $this->modalSubmitActionLabel(__('cortex.actions::restore.single.modal.actions.restore.label'));

        $this->successNotificationTitle(__('cortex.actions::restore.single.notifications.restored.title'));

        $this->color('secondary');

        $this->tableIcon(CortexIcon::resolve('actions::restore-action') ?? Heroicon::ArrowUturnLeft);
        $this->groupedIcon(CortexIcon::resolve('actions::restore-action.grouped') ?? Heroicon::ArrowUturnLeft);

        $this->requiresConfirmation();

        $this->modalIcon(CortexIcon::resolve('actions::restore-action.modal') ?? Heroicon::OutlinedArrowUturnLeft);

        $this->action(function (Model $record): void {
            if (! method_exists($record, 'restore')) {
                $this->failure();

                return;
            }

            $result = $this->process(static fn () => $record->restore());

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
