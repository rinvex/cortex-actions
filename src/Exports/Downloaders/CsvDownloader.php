<?php

declare(strict_types=1);

namespace Cortex\Actions\Exports\Downloaders;

use Cortex\Actions\Exports\Models\Export;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Cortex\Actions\Exports\Downloaders\Contracts\Downloader;

class CsvDownloader implements Downloader
{
    public function __invoke(Export $export): StreamedResponse
    {
        $disk = $export->getFileDisk();
        $directory = $export->getFileDirectory();

        if (! $disk->exists($directory)) {
            abort(404);
        }

        return response()->streamDownload(function () use ($disk, $directory): void {
            echo $disk->get($directory . DIRECTORY_SEPARATOR . 'headers.csv');

            flush();

            foreach ($disk->files($directory) as $file) {
                if (str($file)->endsWith('headers.csv')) {
                    continue;
                }

                if (! str($file)->endsWith('.csv')) {
                    continue;
                }

                echo $disk->get($file);

                flush();
            }
        }, "{$export->file_name}.csv", [
            'Content-Type' => 'text/csv',
        ]);
    }
}
