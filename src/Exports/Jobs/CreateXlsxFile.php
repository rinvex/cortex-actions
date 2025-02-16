<?php

declare(strict_types=1);

namespace Cortex\Actions\Exports\Jobs;

use Illuminate\Http\File;
use League\Csv\Statement;
use Illuminate\Bus\Queueable;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;
use League\Csv\Reader as CsvReader;
use Cortex\Actions\Exports\Exporter;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use OpenSpout\Common\Entity\Style\Style;
use Cortex\Actions\Exports\Models\Export;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Filesystem\Filesystem;

class CreateXlsxFile implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public bool $deleteWhenMissingModels = true;

    protected Exporter $exporter;

    /**
     * @param  array<string, string>  $columnMap
     * @param  array<string, mixed>  $options
     */
    public function __construct(
        protected Export $export,
        protected array $columnMap,
        protected array $options = [],
    ) {
        $this->exporter = $this->export->getExporter(
            $this->columnMap,
            $this->options,
        );
    }

    public function handle(): void
    {
        $disk = $this->export->getFileDisk();

        $writer = app(Writer::class, ['options' => $this->exporter->getXlsxWriterOptions()]);
        $writer->openToFile($temporaryFile = tempnam(sys_get_temp_dir(), $this->export->file_name));

        $csvDelimiter = $this->exporter::getCsvDelimiter();

        $writeRowsFromFile = function (string $file, ?Style $style = null) use ($csvDelimiter, $disk, $writer): void {
            $csvReader = CsvReader::createFromStream($disk->readStream($file));
            $csvReader->setDelimiter($csvDelimiter);
            $csvResults = Statement::create()->process($csvReader);

            foreach ($csvResults->getRecords() as $row) {
                $writer->addRow(Row::fromValues($row, $style));
            }
        };

        $cellStyle = $this->exporter->getXlsxCellStyle();

        $writeRowsFromFile(
            $this->export->getFileDirectory() . DIRECTORY_SEPARATOR . 'headers.csv',
            $this->exporter->getXlsxHeaderCellStyle() ?? $cellStyle,
        );

        foreach ($disk->files($this->export->getFileDirectory()) as $file) {
            if (str($file)->endsWith('headers.csv')) {
                continue;
            }

            if (! str($file)->endsWith('.csv')) {
                continue;
            }

            $writeRowsFromFile($file, $cellStyle);
        }

        $writer->close();

        $disk->putFileAs(
            $this->export->getFileDirectory(),
            new File($temporaryFile),
            "{$this->export->file_name}.xlsx",
            Filesystem::VISIBILITY_PRIVATE,
        );

        unlink($temporaryFile);
    }
}
