<?php

return [

    /*
     * Настройки таблиц
     */

    'datatables' => [
        'ajax' => [
            'index' => [
                'url' => 'back.experts.data',
                'type' => 'POST',
                'data' => 'function(data) { data._token = $(\'meta[name="csrf-token"]\').attr(\'content\'); }',
            ],
        ],
        'table' => [
            'index' => [
                'paging' => true,
                'pagingType' => 'full_numbers',
                'searching' => true,
                'info' => false,
                'searchDelay' => 350,
                'language' => [
                    'url' => '/admin/js/plugins/datatables/locales/russian.json',
                ],
            ],
        ],
        'columns' => [
            'index' => [
                ['data' => 'name', 'name' => 'name', 'title' => 'Имя'],
                ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Дата создания'],
                ['data' => 'updated_at', 'name' => 'updated_at', 'title' => 'Дата обновления'],
                ['data' => 'actions', 'name' => 'actions', 'title' => 'Действия', 'orderable' => false, 'searchable' => false],
            ],
        ],
    ],

    /*
     * Настройки изображений
     */

    'images' => [
        'quality' => 75,
        'conversions' => [
            'og_image' => [
                'default' => [
                    [
                        'name' => 'og_image_default',
                        'size' => [
                            'width' => 968,
                            'height' => 475,
                        ],
                    ],
                ],
            ],
            'preview' => [
                'default' => [
                    [
                        'name' => 'preview_default',
                        'size' => [
                            'width' => 86,
                            'height' => 86,
                        ],
                    ],
                ],
            ],
            'content' => [
                'default' => [
                    [
                        'name' => 'content_admin',
                        'size' => [
                            'width' => 140,
                        ],
                    ],
                    [
                        'name' => 'content_front',
                        'quality' => 70,
                        'fit' => [
                            'width' => 768,
                            'height' => 512,
                        ],
                    ],
                ],
            ],
        ],
    ],
];
