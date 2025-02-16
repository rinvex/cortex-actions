<?php

declare(strict_types=1);

namespace Cortex\Actions\Concerns;

use Closure;
use BackedEnum;

trait HasGroupedIcon
{
    protected string | BackedEnum | Closure | null $groupedIcon = null;

    public function groupedIcon(string | BackedEnum | Closure | null $icon): static
    {
        $this->groupedIcon = $icon;

        return $this;
    }

    public function getGroupedIcon(): string | BackedEnum | null
    {
        return $this->evaluate($this->groupedIcon);
    }
}
