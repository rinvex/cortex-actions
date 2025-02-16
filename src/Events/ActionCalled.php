<?php

declare(strict_types=1);

namespace Cortex\Actions\Events;

use Cortex\Actions\Action;
use Illuminate\Foundation\Events\Dispatchable;

class ActionCalled
{
    use Dispatchable;

    public function __construct(
        protected Action $action,
    ) {}

    public function getAction(): Action
    {
        return $this->action;
    }
}
