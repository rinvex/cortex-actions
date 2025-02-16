<?php

declare(strict_types=1);

namespace Cortex\Actions\Concerns;

use Livewire\Component;
use Cortex\Actions\Contracts\HasActions;

trait BelongsToLivewire
{
    protected Component $livewire;

    public function livewire(Component $livewire): static
    {
        $this->livewire = $livewire;

        return $this;
    }

    public function getLivewire(): ?object
    {
        if (isset($this->livewire)) {
            return $this->livewire;
        }

        if ($livewire = $this->getSchemaComponentContainer()?->getLivewire()) {
            return $livewire;
        }

        if ($livewire = $this->getSchemaComponent()?->getLivewire()) {
            return $livewire;
        }

        if ($livewire = $this->getTable()?->getLivewire()) {
            return $livewire;
        }

        return $this->getGroup()?->getLivewire();
    }

    public function getHasActionsLivewire(): ?HasActions
    {
        $livewire = $this->getLivewire();

        if (! $livewire instanceof HasActions) {
            return null;
        }

        return $livewire;
    }
}
