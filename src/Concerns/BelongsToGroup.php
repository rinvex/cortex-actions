<?php

declare(strict_types=1);

namespace Cortex\Actions\Concerns;

use Cortex\Actions\ActionGroup;

trait BelongsToGroup
{
    protected ?ActionGroup $group = null;

    public function group(?ActionGroup $group): static
    {
        $this->group = $group;

        return $this;
    }

    public function getGroup(): ?ActionGroup
    {
        return $this->group;
    }

    public function getRootGroup(): ?ActionGroup
    {
        $group = $this->getGroup();

        while ($group) {
            $parentGroup = $group->getGroup();

            if (! $parentGroup) {
                break;
            }

            $group = $parentGroup;
        }

        return $group;
    }
}
