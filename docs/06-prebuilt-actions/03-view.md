---
title: View action
---

## Overview

Cortex includes a prebuilt action that is able to view Eloquent records. When the trigger button is clicked, a modal will open with information inside. Cortex uses form fields to structure this information. All form fields are disabled, so they are not editable by the user. You may use it like so:

```php
use Cortex\Actions\ViewAction;
use Cortex\Forms\Components\TextInput;

ViewAction::make()
    ->record($this->post)
    ->form([
        TextInput::make('title')
            ->required()
            ->maxLength(255),
        // ...
    ])
```

If you want to view table rows, you may do so like this:

```php
use Cortex\Actions\ViewAction;
use Cortex\Forms\Components\TextInput;
use Cortex\Tables\Table;

public function table(Table $table): Table
{
    return $table
        ->actions([
            ViewAction::make()
                ->form([
                    TextInput::make('title')
                        ->required()
                        ->maxLength(255),
                    // ...
                ]),
        ]);
}
```

## Customizing data before filling the form

You may wish to modify the data from a record before it is filled into the form. To do this, you may use the `mutateRecordDataUsing()` method to modify the `$data` array, and return the modified version before it is filled into the form:

```php
use Cortex\Actions\ViewAction;

ViewAction::make()
    ->mutateRecordDataUsing(function (array $data): array {
        $data['user_id'] = auth()->id();

        return $data;
    })
```
