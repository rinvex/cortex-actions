<?php

declare(strict_types=1);

namespace Cortex\Actions;

use Cortex\Tables\Table;
use Cortex\Support\Icons\Heroicon;
use Cortex\Support\Facades\CortexIcon;
use Illuminate\Database\Eloquent\Model;
use Cortex\Actions\Concerns\CanCustomizeProcess;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DetachAction extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'detach';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('cortex.actions::detach.single.label'));

        $this->modalHeading(fn (): string => __('cortex.actions::detach.single.modal.heading', ['label' => $this->getRecordTitle()]));

        $this->modalSubmitActionLabel(__('cortex.actions::detach.single.modal.actions.detach.label'));

        $this->successNotificationTitle(__('cortex.actions::detach.single.notifications.detached.title'));

        $this->color('danger');

        $this->icon(CortexIcon::resolve('actions::detach-action') ?? Heroicon::XMark);

        $this->requiresConfirmation();

        $this->modalIcon(CortexIcon::resolve('actions::detach-action.modal') ?? Heroicon::OutlinedXMark);

        $this->action(function (): void {
            $this->process(function (Model $record, Table $table): void {
                /** @var BelongsToMany $relationship */
                $relationship = $table->getRelationship();

                if ($table->allowsDuplicates()) {
                    $record->{$relationship->getPivotAccessor()}->delete();
                } else {
                    $relationship->detach($record);
                }
            });

            $this->success();
        });
    }
}
