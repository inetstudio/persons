<?php

Route::group([
    'namespace' => 'InetStudio\Persons\Contracts\Http\Controllers\Back',
    'middleware' => ['web', 'back.auth'],
    'prefix' => 'back',
], function () {
    Route::any('persons/data', 'PersonsDataControllerContract@data')->name('back.persons.data.index');
    Route::post('persons/slug', 'PersonsUtilityControllerContract@getSlug')->name('back.persons.getSlug');
    Route::post('persons/suggestions', 'PersonsUtilityControllerContract@getSuggestions')->name('back.persons.getSuggestions');

    Route::resource('persons', 'PersonsControllerContract', ['except' => [
        'show',
    ], 'as' => 'back']);
});
