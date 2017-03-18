<?php

/**
 * Public Shop Routes
 */
Route::group([
        'middleware' => ['web', 'laralum.base'],
        'prefix' => 'shop',
        'namespace' => 'Laralum\Shop\Controllers',
        'as' => 'laralum_public::'
    ], function () {
        Route::get('/cart', 'ShopController@cart')->name('shop.cart');
        Route::post('/cart/checkout', 'ShopController@checkout')->name('shop.cart.checkout')->middleware('auth');
        Route::post('/cart/add/{item}', 'ShopController@addItem')->name('shop.cart.add');
        Route::post('/cart/remove/{item}', 'ShopController@removeItem')->name('shop.cart.remove');
        Route::get('/item/{item}', 'ShopController@item')->name('shop.item');
        Route::get('/orders', 'OrdersController@index')->name('shop.orders')->middleware('auth');
        Route::get('/orders/{order}', 'OrdersController@show')->name('shop.order')->middleware('auth');
        Route::get('/{category?}', 'ShopController@index')->name('shop.index');
});

/**
 * Laralum Shop Routes
 */
Route::group([
        'middleware' => ['web', 'laralum.base', 'laralum.auth'],
        'prefix' => config('laralum.settings.base_url'),
        'namespace' => 'Laralum\Shop\Controllers',
        'as' => 'laralum::'
    ], function () {
        Route::get('shop', 'ShopController@index')->name('shop.index');
});
