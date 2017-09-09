<?php

Route::group(['namespace' => 'InetStudio\Experts\Controllers'], function () {
    Route::group(['middleware' => 'web', 'prefix' => 'back'], function () {
        Route::group(['middleware' => 'back.auth'], function () {
            Route::post('experts/slug', 'ExpertsController@getSlug')->name('back.experts.getSlug');
            Route::post('experts/suggestions', 'ExpertsController@getSuggestions')->name('back.experts.getSuggestions');
            Route::any('experts/data', 'ExpertsController@data')->name('back.experts.data');
            Route::resource('experts', 'ExpertsController', ['except' => [
                'show',
            ], 'as' => 'back']);
        });
    });
});
