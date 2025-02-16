<?php

declare(strict_types=1);

namespace Cortex\Actions\Concerns;

use Closure;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

trait CanAccessSelectedRecords
{
    protected bool | Closure $canAccessSelectedRecords = false;

    public function accessSelectedRecords(bool | Closure $condition = true): static
    {
        $this->canAccessSelectedRecords = $condition;

        return $this;
    }

    public function canAccessSelectedRecords(): bool
    {
        return (bool) $this->evaluate($this->canAccessSelectedRecords);
    }

    public function getSelectedRecords(): EloquentCollection | Collection
    {
        if (! $this->canAccessSelectedRecords()) {
            throw new Exception("The action [{$this->getName()}] is attempting to access the selected records from the table, but it is not using [accessSelectedRecords()], so they are not available.");
        }

        return $this->getLivewire()->getSelectedTableRecords($this->shouldFetchSelectedRecords());
    }
}
