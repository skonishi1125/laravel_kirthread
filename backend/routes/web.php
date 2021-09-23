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
  
// かあスレッドとは
Route::get('/about', 'PageController@about')->name('about');

// Auth認証のかかるページ
Route::group(['middleware' => 'auth'], function () {
  Route::post('/store', 'PostController@store')->name('store');
  Route::post('/destroy', 'PostController@destroy')->name('destroy');
  Route::get('/add_reaction/{user_id}/{post_id}/{reaction_number}', 'PostController@addReaction')->name('add_reaction');
  Route::get('/remove_reaction/{user_id}/{post_id}/{reaction_number}', 'PostController@removeReaction')->name('remove_reaction');
  Route::get('/select_reaction/{user_id}/{post_id}/{reaction_number}', 'PostController@selectReaction')->name('select_reaction');
  Route::post('/select_reaction', 'PostController@selectReaction')->name('select_reaction');

  // プロフィール設定
  Route::get('/config/index', 'ConfigController@index')->name('config.index');
  Route::post('/config/store', 'ConfigController@store')->name('config.store');
});
