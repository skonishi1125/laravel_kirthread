<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/','PostController@index')->name('/');
Route::get('/show/{id}', 'PostController@show')->name('show');

Route::group(['middleware' => 'auth'], function () {
  Route::post('/store', 'PostController@store')->name('store');
  Route::get('/destroy', 'PostController@destroy')->name('destroy');

  // プロフィール設定
  Route::get('/config/index', 'ConfigController@index')->name('config.index');
  Route::post('/config/store', 'ConfigController@store')->name('config.store');
});
