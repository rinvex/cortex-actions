<?php

declare(strict_types=1);

return [

    'single' => [

        'label' => 'Trennen',

        'modal' => [

            'heading' => ':label trennen',

            'actions' => [

                'dissociate' => [
                    'label' => 'Trennen',
                ],

            ],

        ],

        'notifications' => [

            'dissociated' => [
                'title' => 'Getrennt',
            ],

        ],

    ],

    'multiple' => [

        'label' => 'Ausgewählte trennen',

        'modal' => [

            'heading' => 'Ausgewählte :label trennen',

            'actions' => [

                'dissociate' => [
                    'label' => 'Ausgewählte trennen',
                ],

            ],

        ],

        'notifications' => [

            'dissociated' => [
                'title' => 'Getrennt',
            ],

        ],

    ],

];
