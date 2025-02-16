<?php

declare(strict_types=1);

namespace Cortex\Actions;

use Cortex\Actions\Concerns\CanExportRecords;

class ExportAction extends Action
{
    use CanExportRecords;
}
