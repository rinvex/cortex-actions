<?php

declare(strict_types=1);

namespace Cortex\Actions;

use Closure;
use Cortex\Support\Icons\Heroicon;
use Cortex\Support\Facades\CortexIcon;
use Illuminate\Database\Eloquent\Model;
use Cortex\Schemas\Contracts\HasSchemas;
use Cortex\Actions\Contracts\HasActions;

class ViewAction extends Action
{
    protected ?Closure $mutateRecordDataUsing = null;

    public static function getDefaultName(): ?string
    {
        return 'view';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('cortex.actions::view.single.label'));

        $this->modalHeading(fn (): string => __('cortex.actions::view.single.modal.heading', ['label' => $this->getRecordTitle()]));

        $this->modalSubmitAction(false);
        $this->modalCancelAction(fn (Action $action) => $action->label(__('cortex.actions::view.single.modal.actions.close.label')));

        $this->color('secondary');

        $this->tableIcon(CortexIcon::resolve('actions::view-action') ?? Heroicon::Eye);
        $this->groupedIcon(CortexIcon::resolve('actions::view-action.grouped') ?? Heroicon::Eye);

        $this->disabledSchema();

        $this->fillForm(function (HasActions & HasSchemas $livewire, Model $record): array {
            if ($translatableContentDriver = $livewire->makeCortexTranslatableContentDriver()) {
                $data = $translatableContentDriver->getRecordAttributesToArray($record);
            } else {
                $data = $record->attributesToArray();
            }

            if ($this->mutateRecordDataUsing) {
                $data = $this->evaluate($this->mutateRecordDataUsing, ['data' => $data]);
            }

            return $data;
        });
    }

    public function mutateRecordDataUsing(?Closure $callback): static
    {
        $this->mutateRecordDataUsing = $callback;

        return $this;
    }
}
