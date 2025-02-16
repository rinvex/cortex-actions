<?php

declare(strict_types=1);

return [

    'single' => [

        'label' => 'Associer',

        'modal' => [

            'heading' => 'Associer :label',

            'fields' => [

                'record_id' => [
                    'label' => 'Enregistrements',
                ],

            ],

            'actions' => [

                'associate' => [
                    'label' => 'Associer',
                ],

                'associate_another' => [
                    'label' => 'Associer & associer un autre',
                ],

            ],

        ],

        'notifications' => [

            'associated' => [
                'title' => 'Associé',
            ],

        ],

    ],

];
