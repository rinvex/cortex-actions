<?php

declare(strict_types=1);

namespace Cortex\Actions;

use Cortex\Actions\Concerns\HasTranslatableLocaleOptions;

class LocaleSwitcher extends SelectAction
{
    use HasTranslatableLocaleOptions;

    public static function getDefaultName(): ?string
    {
        return 'activeLocale';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('cortex.panesl::actions.active_locale.label'));

        $this->setTranslatableLocaleOptions();
    }
}
