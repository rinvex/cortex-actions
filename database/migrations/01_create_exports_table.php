<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(config('cortex.actions.tables.exports'), function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->timestamp('completed_at')->nullable();
            $table->string('file_disk');
            $table->string('file_name')->nullable();
            $table->string('exporter');
            $table->unsignedInteger('processed_rows')->default(0);
            $table->unsignedInteger('total_rows');
            $table->unsignedInteger('successful_rows')->default(0);
            $table->uuidMorphs('user');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('cortex.actions.tables.exports'));
    }
};
