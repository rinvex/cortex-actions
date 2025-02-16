<?php

declare(strict_types=1);

namespace Cortex\Actions\Exports\Models;

use Exception;
use Carbon\CarbonInterface;
use Cortex\Auth\Models\User;
use Cortex\Actions\Exports\Exporter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property CarbonInterface | null $completed_at
 * @property string $file_disk
 * @property string $file_name
 * @property class-string<Exporter> $exporter
 * @property int $processed_rows
 * @property int $total_rows
 * @property int $successful_rows
 * @property-read Authenticatable $user
 */
class Export extends Model
{
    use Prunable;
    use HasUuids;

    protected $casts = [
        'completed_at' => 'timestamp',
        'processed_rows' => 'integer',
        'total_rows' => 'integer',
        'successful_rows' => 'integer',
    ];

    protected $guarded = [];

    protected static bool $hasPolymorphicUserRelationship = false;

    public function __construct(array $attributes = [])
    {
        $this->setTable(config('cortex.actions.tables.exports') ?: parent::getTable());

        parent::__construct($attributes);
    }

    public function user(): BelongsTo
    {
        if (static::hasPolymorphicUserRelationship()) {
            return $this->morphTo();
        }

        $authenticatable = app(Authenticatable::class);

        if ($authenticatable) {
            /** @phpstan-ignore-next-line */
            return $this->belongsTo($authenticatable::class);
        }

        if (! class_exists(User::class)) {
            throw new Exception('No [Cortex\\Auth\\Models\\User] model found. Please bind an authenticatable model to the [Illuminate\\Contracts\\Auth\\Authenticatable] interface in a service provider\'s [register()] method.');
        }

        /** @phpstan-ignore-next-line */
        return $this->belongsTo(User::class);
    }

    /**
     * @param  array<string, string>  $columnMap
     * @param  array<string, mixed>  $options
     */
    public function getExporter(
        array $columnMap,
        array $options,
    ): Exporter {
        return app($this->exporter, [
            'export' => $this,
            'columnMap' => $columnMap,
            'options' => $options,
        ]);
    }

    public function getFailedRowsCount(): int
    {
        return $this->total_rows - $this->successful_rows;
    }

    public static function polymorphicUserRelationship(bool $condition = true): void
    {
        static::$hasPolymorphicUserRelationship = $condition;
    }

    public static function hasPolymorphicUserRelationship(): bool
    {
        return static::$hasPolymorphicUserRelationship;
    }

    public function getFileDisk(): Filesystem
    {
        return Storage::disk($this->file_disk);
    }

    public function getFileDirectory(): string
    {
        return 'cortex_exports' . DIRECTORY_SEPARATOR . $this->getKey();
    }
}
