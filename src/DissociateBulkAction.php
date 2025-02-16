<?php

declare(strict_types=1);

namespace Cortex\Actions;

use Cortex\Tables\Table;
use Cortex\Support\Icons\Heroicon;
use Cortex\Support\Facades\CortexIcon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Cortex\Actions\Concerns\CanCustomizeProcess;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DissociateBulkAction extends BulkAction
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'dissociate';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('cortex.actions::dissociate.multiple.label'));

        $this->modalHeading(fn (): string => __('cortex.actions::dissociate.multiple.modal.heading', ['label' => $this->getTitleCasePluralModelLabel()]));

        $this->modalSubmitActionLabel(__('cortex.actions::dissociate.multiple.modal.actions.dissociate.label'));

        $this->successNotificationTitle(__('cortex.actions::dissociate.multiple.notifications.dissociated.title'));

        $this->color('danger');

        $this->icon(CortexIcon::resolve('actions::dissociate-action') ?? Heroicon::XMark);

        $this->requiresConfirmation();

        $this->modalIcon(CortexIcon::resolve('actions::dissociate-action.modal') ?? Heroicon::OutlinedXMark);

        $this->action(function (): void {
            $this->process(function (Collection $records, Table $table): void {
                $records->each(function (Model $record) use ($table): void {
                    /** @var BelongsTo $inverseRelationship */
                    $inverseRelationship = $table->getInverseRelationshipFor($record);

                    $inverseRelationship->dissociate();
                    $record->save();
                });
            });

            $this->success();
        });

        $this->deselectRecordsAfterCompletion();
    }
}
