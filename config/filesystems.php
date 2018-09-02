<?php

return [

    /*
     * Расширение файла конфигурации app/config/filesystems.php
     * добавляет локальные диски для хранения изображений экспертов
     */

    'persons' => [
        'driver' => 'local',
        'root' => storage_path('app/public/persons'),
        'url' => env('APP_URL').'/storage/persons',
        'visibility' => 'public',
    ],

];
