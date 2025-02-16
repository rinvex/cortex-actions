<?php

declare(strict_types=1);

namespace Cortex\Actions\Concerns;

use Closure;
use Cortex\Actions\Action;
use Cortex\Actions\ActionGroup;
use Cortex\Schemas\Components\Component;

trait HasInfolist
{
    /**
     * @param  array<Component | Action | ActionGroup> | Closure | null  $infolist
     */
    public function infolist(array | Closure | null $infolist): static
    {
        $this->schema($infolist);

        return $this;
    }
}
