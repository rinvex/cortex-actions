<?php

declare(strict_types=1);

namespace Cortex\Actions;

use Cortex\Tables\Table;
use Cortex\Support\Icons\Heroicon;
use Cortex\Support\Facades\CortexIcon;
use Illuminate\Database\Eloquent\Model;
use Cortex\Actions\Concerns\CanCustomizeProcess;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DissociateAction extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'dissociate';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('cortex.actions::dissociate.single.label'));

        $this->modalHeading(fn (): string => __('cortex.actions::dissociate.single.modal.heading', ['label' => $this->getRecordTitle()]));

        $this->modalSubmitActionLabel(__('cortex.actions::dissociate.single.modal.actions.dissociate.label'));

        $this->successNotificationTitle(__('cortex.actions::dissociate.single.notifications.dissociated.title'));

        $this->color('danger');

        $this->icon(CortexIcon::resolve('actions::dissociate-action') ?? Heroicon::XMark);

        $this->requiresConfirmation();

        $this->modalIcon(CortexIcon::resolve('actions::dissociate-action.modal') ?? Heroicon::OutlinedXMark);

        $this->action(function (): void {
            $this->process(function (Model $record, Table $table): void {
                /** @var BelongsTo $inverseRelationship */
                $inverseRelationship = $table->getInverseRelationshipFor($record);

                $inverseRelationship->dissociate();
                $record->save();
            });

            $this->success();
        });
    }
}
