<?php

declare(strict_types=1);

return [

    'single' => [

        'label' => 'Ripristina',

        'modal' => [

            'heading' => 'Ripristina :label',

            'actions' => [

                'restore' => [
                    'label' => 'Ripristina',
                ],

            ],

        ],

        'notifications' => [

            'restored' => [
                'title' => 'Ripristinato',
            ],

        ],

    ],

    'multiple' => [

        'label' => 'Ripristina selezionati',

        'modal' => [

            'heading' => 'Ripristina selezionati :label',

            'actions' => [

                'restore' => [
                    'label' => 'Ripristina',
                ],

            ],

        ],

        'notifications' => [

            'restored' => [
                'title' => 'Ripristinati',
            ],

        ],

    ],

];
