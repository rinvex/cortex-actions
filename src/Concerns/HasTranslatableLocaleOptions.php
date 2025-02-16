<?php

declare(strict_types=1);

namespace Cortex\Actions\Concerns;

trait HasTranslatableLocaleOptions
{
    public function setTranslatableLocaleOptions(): static
    {
        $this->options(function (): array {
            $livewire = $this->getLivewire();

            if (! method_exists($livewire, 'getTranslatableLocales')) {
                return [];
            }

            $locales = [];

            foreach ($livewire->getTranslatableLocales() as $translatableLocale) {
                $locales[$translatableLocale] = cortex()->getCurrentOrDefaultPanel()->getLocaleLabel($translatableLocale) ?? $translatableLocale;
            }

            return $locales;
        });

        return $this;
    }
}
