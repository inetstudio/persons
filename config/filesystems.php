<?php

return [

    /*
     * Расширение файла конфигурации app/config/filesystems.php
     * добавляет локальные диски для хранения изображений экспертов
     */

    'experts' => [
        'driver' => 'local',
        'root' => storage_path('app/public/experts/'),
        'url' => env('APP_URL').'/storage/experts/',
        'visibility' => 'public',
    ],

];
