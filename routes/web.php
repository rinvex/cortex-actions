<?php

declare(strict_types=1);

use Cortex\Panels\Panel;
use Illuminate\Support\Facades\Route;
use Cortex\Actions\Exports\Http\Controllers\DownloadExport;
use Cortex\Actions\Imports\Http\Controllers\DownloadImportFailureCsv;

collect(cortex()->getPanels())->each->routes(function (Panel $panel): void {
    Route::get('exports/{export}/download', DownloadExport::class)
        ->name('exports.download')
        ->middleware('cortex.actions');

    Route::get('imports/{import}/failed-rows/download', DownloadImportFailureCsv::class)
        ->name('imports.failed-rows.download')
        ->middleware('cortex.actions');
});
