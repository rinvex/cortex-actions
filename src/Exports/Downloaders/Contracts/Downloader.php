<?php

declare(strict_types=1);

namespace Cortex\Actions\Exports\Downloaders\Contracts;

use Cortex\Actions\Exports\Models\Export;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface Downloader
{
    public function __invoke(Export $export): StreamedResponse;
}
