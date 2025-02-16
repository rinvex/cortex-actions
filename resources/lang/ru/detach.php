<?php

declare(strict_types=1);

return [

    'single' => [

        'label' => 'Открепить',

        'modal' => [

            'heading' => 'Открепить :label',

            'actions' => [

                'detach' => [
                    'label' => 'Открепить',
                ],

            ],

        ],

        'notifications' => [

            'detached' => [
                'title' => 'Откреплено',
            ],

        ],

    ],

    'multiple' => [

        'label' => 'Открепить',

        'modal' => [

            'heading' => 'Открепить отмеченное :label',

            'actions' => [

                'detach' => [
                    'label' => 'Открепить отмеченное',
                ],

            ],

        ],

        'notifications' => [

            'detached' => [
                'title' => 'Откреплено',
            ],

        ],

    ],

];
