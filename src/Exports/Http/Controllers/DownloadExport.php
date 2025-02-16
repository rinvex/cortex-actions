<?php

declare(strict_types=1);

namespace Cortex\Actions\Exports\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Cortex\Actions\Exports\Models\Export;
use Cortex\Actions\Exports\Enums\ExportFormat;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadExport
{
    public function __invoke(Request $request, Export $export): StreamedResponse
    {
        abort_unless(auth(
            $request->hasValidSignature(absolute: false)
                ? $request->query('authGuard')
                : null,
        )->check(), 401);

        $user = auth(
            $request->hasValidSignature(absolute: false)
                ? $request->query('authGuard')
                : null,
        )->user();

        $exportPolicy = Gate::getPolicyFor($export::class);

        if (filled($exportPolicy) && method_exists($exportPolicy, 'view')) {
            Gate::forUser($user)->authorize('view', $export);
        } else {
            abort_unless($export->user()->is($user), 403);
        }

        $format = ExportFormat::tryFrom($request->query('format'));

        abort_unless($format !== null, 404);

        return $format->getDownloader()($export);
    }
}
