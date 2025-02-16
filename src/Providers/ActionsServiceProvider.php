<?php

declare(strict_types=1);

namespace Cortex\Actions\Providers;

use Illuminate\Routing\Router;
use Cortex\Actions\Testing\TestsActions;
use Cortex\Actions\Exports\Models\Export;
use Cortex\Actions\Imports\Models\Import;
use Livewire\Features\SupportTesting\Testable;
use Rinvex\Packages\Providers\PackageServiceProvider;

class ActionsServiceProvider extends PackageServiceProvider
{
    public function packageRegistered(): void
    {
        app(Router::class)->middlewareGroup('cortex.actions', ['web']);
    }

    public function packageBooted(): void
    {
        Testable::mixin(new TestsActions);
        Import::polymorphicUserRelationship();
        Export::polymorphicUserRelationship();
    }
}
