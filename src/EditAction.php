<?php

declare(strict_types=1);

namespace Cortex\Actions;

use Closure;
use Cortex\Tables\Table;
use Illuminate\Support\Arr;
use Cortex\Support\Icons\Heroicon;
use Cortex\Support\Facades\CortexIcon;
use Illuminate\Database\Eloquent\Model;
use Cortex\Actions\Contracts\HasActions;
use Cortex\Schemas\Contracts\HasSchemas;
use Cortex\Actions\Concerns\CanCustomizeProcess;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EditAction extends Action
{
    use CanCustomizeProcess;

    protected ?Closure $mutateRecordDataUsing = null;

    public static function getDefaultName(): ?string
    {
        return 'edit';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('cortex.actions::edit.single.label'));

        $this->modalHeading(fn (): string => __('cortex.actions::edit.single.modal.heading', ['label' => $this->getRecordTitle()]));

        $this->modalSubmitActionLabel(__('cortex.actions::edit.single.modal.actions.save.label'));

        $this->successNotificationTitle(__('cortex.actions::edit.single.notifications.saved.title'));

        $this->tableIcon(CortexIcon::resolve('actions::edit-action') ?? Heroicon::PencilSquare);
        $this->groupedIcon(CortexIcon::resolve('actions::edit-action.grouped') ?? Heroicon::PencilSquare);

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

        $this->action(function (): void {
            $this->process(function (array $data, HasActions & HasSchemas $livewire, Model $record, ?Table $table): void {
                $relationship = $table?->getRelationship();

                $translatableContentDriver = $livewire->makeCortexTranslatableContentDriver();

                if ($relationship instanceof BelongsToMany) {
                    $pivot = $record->{$relationship->getPivotAccessor()};

                    $pivotColumns = $relationship->getPivotColumns();
                    $pivotData = Arr::only($data, $pivotColumns);

                    if (count($pivotColumns)) {
                        if ($translatableContentDriver) {
                            $translatableContentDriver->updateRecord($pivot, $pivotData);
                        } else {
                            $pivot->update($pivotData);
                        }
                    }

                    $data = Arr::except($data, $pivotColumns);
                }

                if ($translatableContentDriver) {
                    $translatableContentDriver->updateRecord($record, $data);
                } else {
                    $record->update($data);
                }
            });

            $this->success();
        });
    }

    public function mutateRecordDataUsing(?Closure $callback): static
    {
        $this->mutateRecordDataUsing = $callback;

        return $this;
    }
}
