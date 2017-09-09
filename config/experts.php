<?php

return [

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
        ]
    ],
];
