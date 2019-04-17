<?php

return [

    /*
     * Настройки изображений
     */

    'images' => [
        'quality' => 75,
        'conversions' => [
            'person' => [
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
                    'square' => [
                        [
                            'name' => 'preview_square_sidebar',
                            'size' => [
                                'width' => 86,
                                'height' => 86,
                            ],
                        ],
                        [
                            'name' => 'preview_square_list',
                            'size' => [
                                'width' => 140,
                                'height' => 140,
                            ],
                        ],
                    ],
                    '3_2' => [
                        [
                            'name' => 'preview_3_2',
                            'size' => [
                                'width' => 768,
                                'height' => 512,
                            ],
                        ],
                        [
                            'name' => 'preview_3_2_optimize',
                            'quality' => 65,
                            'size' => [
                                'width' => 768,
                                'height' => 512,
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
        'crops' => [
            'person' => [
                'preview' => [
                    [
                        'title' => 'Квадратная область',
                        'name' => 'square',
                        'ratio' => '300/300',
                        'size' => [
                            'width' => 300,
                            'height' => 300,
                            'type' => 'min',
                            'description' => 'Минимальный размер области — 300x300 пикселей',
                        ],
                    ],
                    [
                        'title' => 'Размер 3х2',
                        'name' => '3_2',
                        'ratio' => '3/2',
                        'size' => [
                            'width' => 768,
                            'height' => 512,
                            'type' => 'min',
                            'description' => 'Минимальный размер области 3x2 — 768x512 пикселей',
                        ],
                    ],
                ],
            ],
        ],
    ],
];
