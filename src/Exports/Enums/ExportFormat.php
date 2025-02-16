<?php

declare(strict_types=1);

namespace Cortex\Actions\Exports\Enums;

use Illuminate\Support\Facades\URL;
use Cortex\Actions\Exports\Models\Export;
use Cortex\Actions\Action as NotificationAction;
use Cortex\Actions\Exports\Downloaders\CsvDownloader;
use Cortex\Actions\Exports\Downloaders\XlsxDownloader;
use Cortex\Actions\Exports\Downloaders\Contracts\Downloader;

enum ExportFormat: string
{
    case Csv = 'csv';

    case Xlsx = 'xlsx';

    public function getDownloader(): Downloader
    {
        return match ($this) {
            self::Csv => app(CsvDownloader::class),
            self::Xlsx => app(XlsxDownloader::class),
        };
    }

    public function getDownloadNotificationAction(Export $export, string $authGuard): NotificationAction
    {
        $panelId = cortex()->getCurrentOrDefaultPanel()->getId();

        return NotificationAction::make("download_{$this->value}")
            ->label(__("cortex.actions::export.notifications.completed.actions.download_{$this->value}.label"))
            ->url(URL::signedRoute("cortex.{$panelId}.exports.download", ['authGuard' => $authGuard, 'export' => $export, 'format' => $this], absolute: false), shouldOpenInNewTab: true)
            ->markAsRead();
    }
}
