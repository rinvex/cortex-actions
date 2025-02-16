<?php

declare(strict_types=1);

namespace Cortex\Actions;

use Cortex\Support\Icons\Heroicon;
use Cortex\Support\Facades\CortexIcon;

class BulkActionGroup extends ActionGroup
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('cortex.tables::table.actions.open_bulk_actions.label'));

        $this->icon(CortexIcon::resolve('tables::actions.open-bulk-actions') ?? Heroicon::EllipsisVertical);

        $this->color('secondary');

        $this->button();

        $this->dropdownPlacement('bottom-start');

        $this->labeledFrom('sm');
    }
}
