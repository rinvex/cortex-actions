<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(config('cortex.actions.tables.import_failures'), function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->json('data');
            $table->foreignUuid('import_id')->constrained(config('cortex.actions.tables.imports'))->cascadeOnUpdate()->cascadeOnDelete();
            $table->text('validation_error')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('cortex.actions.tables.import_failures'));
    }
};
