<?php

declare(strict_types=1);

namespace Cortex\Actions;

use Illuminate\Support\Number;
use Cortex\Support\Icons\Heroicon;
use Cortex\Tables\Contracts\HasTable;
use Cortex\Support\Facades\CortexIcon;
use Illuminate\Database\Eloquent\Model;
use Cortex\Tables\Filters\TrashedFilter;
use Cortex\Actions\Concerns\CanCustomizeProcess;

class DeleteBulkAction extends BulkAction
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'delete';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('cortex.actions::delete.multiple.label'));

        $this->modalHeading(fn (): string => __('cortex.actions::delete.multiple.modal.heading', ['label' => $this->getTitleCasePluralModelLabel()]));

        $this->modalSubmitActionLabel(__('cortex.actions::delete.multiple.modal.actions.delete.label'));

        $this->successNotificationTitle(__('cortex.actions::delete.multiple.notifications.deleted.title'));

        $this->failureNotificationTitle(function (int $successCount, int $totalCount): string {
            if ($successCount) {
                return trans_choice('cortex.actions::delete.multiple.notifications.deleted_partial.title', $successCount, [
                    'count' => Number::format($successCount),
                    'total' => Number::format($totalCount),
                ]);
            }

            return trans_choice('cortex.actions::delete.multiple.notifications.deleted_none.title', $totalCount, [
                'count' => Number::format($totalCount),
                'total' => Number::format($totalCount),
            ]);
        });

        $this->failureNotificationMissingMessage(function (int $missingMessageCount, int $successCount): string {
            return trans_choice(
                $successCount
                    ? 'cortex.actions::delete.multiple.notifications.deleted_partial.missing_message'
                    : 'cortex.actions::delete.multiple.notifications.deleted_none.missing_message',
                $missingMessageCount,
                ['count' => Number::format($missingMessageCount)],
            );
        });

        $this->color('danger');

        $this->icon(CortexIcon::resolve('actions::delete-action') ?? Heroicon::Trash);

        $this->requiresConfirmation();

        $this->modalIcon(CortexIcon::resolve('actions::delete-action.modal') ?? Heroicon::OutlinedTrash);

        $this->action(fn () => $this->processIndividualRecords(
            static fn (Model $record) => $record->delete(),
        ));

        $this->deselectRecordsAfterCompletion();

        $this->hidden(function (HasTable $livewire): bool {
            $trashedFilterState = $livewire->getTableFilterState(TrashedFilter::class) ?? [];

            if (! array_key_exists('value', $trashedFilterState)) {
                return false;
            }

            if ($trashedFilterState['value']) {
                return false;
            }

            return filled($trashedFilterState['value']);
        });
    }
}
