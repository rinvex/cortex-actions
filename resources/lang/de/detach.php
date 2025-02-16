<?php

declare(strict_types=1);

return [

    'single' => [

        'label' => 'Trennen',

        'modal' => [

            'heading' => ':label trennen',

            'actions' => [

                'detach' => [
                    'label' => 'Trennen',
                ],

            ],

        ],

        'notifications' => [

            'detached' => [
                'title' => 'Getrennt',
            ],

        ],

    ],

    'multiple' => [

        'label' => 'Ausgewählte trennen',

        'modal' => [

            'heading' => 'Ausgewählte :label trennen',

            'actions' => [

                'detach' => [
                    'label' => 'Ausgewählte trennen',
                ],

            ],

        ],

        'notifications' => [

            'detached' => [
                'title' => 'Getrennt',
            ],

        ],

    ],

];
