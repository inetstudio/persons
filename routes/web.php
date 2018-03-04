<?php

Route::group([
    'namespace' => 'InetStudio\Experts\Contracts\Http\Controllers\Back',
    'middleware' => ['web', 'back.auth'],
    'prefix' => 'back',
], function () {
    Route::any('experts/data', 'ExpertsDataControllerContract@data')->name('back.experts.data.index');
    Route::post('experts/slug', 'ExpertsUtilityControllerContract@getSlug')->name('back.experts.getSlug');
    Route::post('experts/suggestions', 'ExpertsUtilityControllerContract@getSuggestions')->name('back.experts.getSuggestions');

    Route::resource('experts', 'ExpertsControllerContract', ['except' => [
        'show',
    ], 'as' => 'back']);
});
