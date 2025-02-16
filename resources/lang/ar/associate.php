<?php

declare(strict_types=1);

return [

    'single' => [

        'label' => 'ارتباط',

        'modal' => [

            'heading' => 'ربط :label',

            'fields' => [

                'record_id' => [
                    'label' => 'السجلات',
                ],

            ],

            'actions' => [

                'associate' => [
                    'label' => 'ربط',
                ],

                'associate_another' => [
                    'label' => 'ربط وبدء ربط المزيد',
                ],

            ],

        ],

        'notifications' => [

            'associated' => [
                'title' => 'تم الربط',
            ],

        ],

    ],

];
