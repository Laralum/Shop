<?php

Route::group([
        'middleware' => ['web', 'laralum.base', 'laralum.auth'],
        'prefix' => config('laralum.settings.base_url'),
        'namespace' => 'Laralum\Shop\Controllers',
        'as' => 'laralum::'
    ], function () {
        Route::get('shop', 'ShopController@index')->name('shop.index');
});
