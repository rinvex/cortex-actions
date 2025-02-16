<?php

declare(strict_types=1);

return [

    'single' => [

        'label' => 'Löschen',

        'modal' => [

            'heading' => ':label löschen',

            'actions' => [

                'delete' => [
                    'label' => 'Löschen',
                ],

            ],

        ],

        'notifications' => [

            'deleted' => [
                'title' => 'Gelöscht',
            ],

        ],

    ],

    'multiple' => [

        'label' => 'Ausgewählte löschen',

        'modal' => [

            'heading' => 'Ausgewählte :label löschen',

            'actions' => [

                'delete' => [
                    'label' => 'Ausgewählte löschen',
                ],

            ],

        ],

        'notifications' => [

            'deleted' => [
                'title' => 'Gelöscht',
            ],

        ],

    ],

];
