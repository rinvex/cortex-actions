<?php

declare(strict_types=1);

namespace Cortex\Actions\Concerns;

use Closure;
use Cortex\Actions\Action;
use Cortex\Schemas\Schema;
use Cortex\Actions\ActionGroup;
use Cortex\Schemas\Components\Wizard;
use Cortex\Schemas\Components\Component;

trait HasSchema
{
    /**
     * @var array<Component | Action | ActionGroup> | Closure | null
     */
    protected array | Closure | null $schema = null;

    protected bool | Closure $isSchemaDisabled = false;

    /**
     * @param  array<Component | Action | ActionGroup> | Closure | null  $schema
     */
    public function components(array | Closure | null $schema): static
    {
        $this->schema = $schema;

        return $this;
    }

    /**
     * @param  array<Component | Action | ActionGroup> | Closure | null  $schema
     */
    public function schema(array | Closure | null $schema): static
    {
        $this->components($schema);

        return $this;
    }

    public function disabledSchema(bool | Closure $condition = true): static
    {
        $this->isSchemaDisabled = $condition;

        return $this;
    }

    public function isSchemaDisabled(): bool
    {
        return (bool) $this->evaluate($this->isSchemaDisabled);
    }

    public function getSchema(Schema $schema): ?Schema
    {
        $modifiedSchema = $this->evaluate($this->schema ?? $this->getHasActionsLivewire()?->getDefaultActionSchemaResolver($this), [
            'form' => $schema,
            'schema' => $schema,
            'infolist' => $schema,
        ]);

        if ($modifiedSchema === null) {
            return null;
        }

        if (is_array($modifiedSchema) && (! count($modifiedSchema))) {
            return null;
        }

        if (is_array($modifiedSchema) && $this->isWizard()) {
            $wizard = Wizard::make($modifiedSchema)
                ->contained(false)
                ->startOnStep($this->getWizardStartStep())
                ->cancelAction($this->getModalCancelAction())
                ->submitAction($this->getModalSubmitAction())
                ->skippable($this->isWizardSkippable())
                ->disabled($this->isSchemaDisabled());

            if ($this->modifyWizardUsing) {
                $wizard = $this->evaluate($this->modifyWizardUsing, [
                    'wizard' => $wizard,
                ]) ?? $wizard;
            }

            $modifiedSchema = [$wizard];
        }

        if (is_array($modifiedSchema)) {
            $modifiedSchema = $schema->schema($modifiedSchema);
        }

        if ($this->isSchemaDisabled()) {
            return $modifiedSchema->disabled();
        }

        return $modifiedSchema;
    }
}
