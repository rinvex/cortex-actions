<?php

declare(strict_types=1);

namespace Cortex\Actions\Concerns;

use Closure;
use BackedEnum;

trait HasTableIcon
{
    protected string | BackedEnum | Closure | null $tableIcon = null;

    public function tableIcon(string | BackedEnum | Closure | null $icon): static
    {
        $this->tableIcon = $icon;

        return $this;
    }

    public function getTableIcon(): string | BackedEnum | null
    {
        return $this->evaluate($this->tableIcon);
    }
}
