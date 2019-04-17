<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        'namespace' => 'InetStudio\PersonsPackage\Persons\Contracts\Http\Controllers\Back',
        'middleware' => ['web', 'back.auth'],
        'prefix' => 'back',
    ],
    function () {
        Route::any('persons/data', 'DataControllerContract@data')
            ->name('back.persons.data.index');

        Route::post('persons/slug', 'UtilityControllerContract@getSlug')
            ->name('back.persons.getSlug');

        Route::post('persons/suggestions', 'UtilityControllerContract@getSuggestions')
            ->name('back.persons.getSuggestions');

        Route::resource(
            'persons',
            'ResourceControllerContract',
            [
                'except' => [
                    'show',
                ],
                'as' => 'back',
            ]
        );
    }
);
