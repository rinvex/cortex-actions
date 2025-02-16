<?php

declare(strict_types=1);

namespace Cortex\Actions\Exports\Jobs;

use Illuminate\Bus\Queueable;
use Cortex\Actions\Exports\Exporter;
use Cortex\Notifications\Notification;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Cortex\Actions\Exports\Models\Export;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Cortex\Actions\Exports\Enums\ExportFormat;
use Illuminate\Contracts\Auth\Authenticatable;
use Cortex\Actions\Action as NotificationAction;

class ExportCompletion implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public bool $deleteWhenMissingModels = true;

    protected Exporter $exporter;

    /**
     * @param  array<string, string>  $columnMap
     * @param  array<ExportFormat>  $formats
     * @param  array<string, mixed>  $options
     */
    public function __construct(
        protected Export $export,
        protected array $columnMap,
        protected array $formats,
        protected array $options,
        protected string $authGuard,
    ) {
        $this->exporter = $this->export->getExporter(
            $this->columnMap,
            $this->options,
        );
    }

    public function handle(): void
    {
        $this->export->touch('completed_at');

        if (! $this->export->user instanceof Authenticatable) { /** @phpstan-ignore instanceof.alwaysTrue */
            return;
        }

        $failedRowsCount = $this->export->getFailedRowsCount();

        Notification::make()
            ->title($this->exporter::getCompletedNotificationTitle($this->export))
            ->body($this->exporter::getCompletedNotificationBody($this->export))
            ->when(
                ! $failedRowsCount,
                fn (Notification $notification) => $notification->success(),
            )
            ->when(
                $failedRowsCount && ($failedRowsCount < $this->export->total_rows),
                fn (Notification $notification) => $notification->warning(),
            )
            ->when(
                $failedRowsCount === $this->export->total_rows,
                fn (Notification $notification) => $notification->danger(),
            )
            ->when(
                $failedRowsCount < $this->export->total_rows,
                fn (Notification $notification) => $notification->actions(array_map(
                    fn (ExportFormat $format): NotificationAction => $format->getDownloadNotificationAction($this->export, $this->authGuard),
                    $this->formats,
                )),
            )
            ->when(
                ($this->connection === 'sync') ||
                    (blank($this->connection) && (config('queue.default') === 'sync')),
                fn (Notification $notification) => $notification
                    ->persistent()
                    ->send(),
                fn (Notification $notification) => $notification->sendToDatabase($this->export->user, isEventDispatched: true),
            );
    }
}
