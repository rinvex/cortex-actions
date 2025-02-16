<?php

declare(strict_types=1);

namespace Cortex\Actions;

use Cortex\Actions\Concerns\CanExportRecords;

class ExportBulkAction extends BulkAction
{
    use CanExportRecords {
        setUp as baseSetUp;
    }

    protected function setUp(): void
    {
        $this->baseSetUp();

        $this->fetchSelectedRecords(false);
    }
}
