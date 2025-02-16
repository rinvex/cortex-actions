<?php

declare(strict_types=1);

namespace Cortex\Actions\Imports\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property array $data
 * @property string | null $validation_error
 * @property-read Import $import
 */
class FailedImportRow extends Model
{
    use Prunable;
    use HasUuids;

    protected $casts = [
        'data' => 'array',
    ];

    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        $this->setTable(config('cortex.actions.tables.import_failures') ?: parent::getTable());

        parent::__construct($attributes);
    }

    public function import(): BelongsTo
    {
        return $this->belongsTo(app(Import::class)::class);
    }

    public function prunable(): Builder
    {
        return static::where(
            'created_at',
            '<=',
            now()->subMonth(),
        );
    }
}
