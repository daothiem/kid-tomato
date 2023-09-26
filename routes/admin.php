<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/admin'], function () {
    Route::get('/login', ['uses' => 'Admin\AuthController@getLogin'])->name('admin.login');
    Route::post('/login', ['uses' => 'Admin\AuthController@postLogin'])->name('admin.login');
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/', ['uses' => 'Admin\AdminController@index'])->name('admin.index');
        Route::get('/tin-tuc', ['uses' => 'Admin\NewsController@index'])->name('admin.news.index');
        Route::get('/danh-muc-tin-tuc', ['uses' => 'Admin\NewsCategoryController@index'])->name('admin.newsCategory.index');
        Route::get('/them-moi/danh-muc-tin-tuc', ['uses' => 'Admin\NewsCategoryController@create'])->name('admin.newsCategory.create');
        Route::post('/them-moi/danh-muc-tin-tuc', ['uses' => 'Admin\NewsCategoryController@store'])->name('admin.newsCategory.store');
        Route::get('/{id}/danh-muc-tin-tuc', ['uses' => 'Admin\NewsCategoryController@edit'])->name('admin.newsCategory.edit');
        Route::put('/{id}/danh-muc-tin-tuc', ['uses' => 'Admin\NewsCategoryController@update'])->name('admin.newsCategory.update');
        Route::delete('/{id}/danh-muc-tin-tuc', ['uses' => 'Admin\NewsCategoryController@destroy'])->name('admin.newsCategory.destroy');

        Route::get('/tin-tuc', ['uses' => 'Admin\NewsController@index'])->name('admin.news.index');
        Route::get('/them-moi/tin-tuc', ['uses' => 'Admin\NewsController@create'])->name('admin.news.create');
        Route::post('/them-moi/tin-tuc', ['uses' => 'Admin\NewsController@store'])->name('admin.news.store');
        Route::put('/{id}/tin-tuc', ['uses' => 'Admin\NewsController@update'])->name('admin.news.update');
        Route::get('/{id}/tin-tuc', ['uses' => 'Admin\NewsController@edit'])->name('admin.news.edit');
        Route::delete('/{id}/tin-tuc', ['uses' => 'Admin\NewsController@destroy'])->name('admin.news.destroy');

        Route::get('/danh-muc', ['uses' => 'Admin\CategoryController@index'])->name('admin.categories.index');
        Route::get('/them-moi/danh-muc', ['uses' => 'Admin\CategoryController@create'])->name('admin.categories.create');
        Route::post('/them-moi/danh-muc', ['uses' => 'Admin\CategoryController@store'])->name('admin.categories.store');
        Route::put('/{id}/danh-muc', ['uses' => 'Admin\CategoryController@update'])->name('admin.categories.update');
        Route::get('/{id}/danh-muc', ['uses' => 'Admin\CategoryController@edit'])->name('admin.categories.edit');
        Route::delete('/{id}/danh-muc', ['uses' => 'Admin\CategoryController@destroy'])->name('admin.categories.destroy');

        Route::get('/san-pham', ['uses' => 'Admin\ProductController@index'])->name('admin.products.index');
        Route::get('/them-moi/san-pham', ['uses' => 'Admin\ProductController@create'])->name('admin.products.create');
        Route::post('/them-moi/san-pham', ['uses' => 'Admin\ProductController@store'])->name('admin.products.store');
        Route::put('/{id}/san-pham', ['uses' => 'Admin\ProductController@update'])->name('admin.products.update');
        Route::get('/{id}/san-pham', ['uses' => 'Admin\ProductController@edit'])->name('admin.products.edit');
        Route::delete('/{id}/san-pham', ['uses' => 'Admin\ProductController@destroy'])->name('admin.products.destroy');
    });
});

