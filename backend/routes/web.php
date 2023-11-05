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

// ゲーム
Route::get('/game', 'PageController@game')->name('game');
Route::get('/game/panel', 'PageController@panel')->name('game_panel');

// Auth認証のかかるページ
Route::group(['middleware' => 'auth'], function () {
  Route::post('/store', 'PostController@store')->name('store');
  Route::post('/destroy', 'PostController@destroy')->name('destroy');
  Route::get('/add_reaction/{user_id}/{post_id}/{reaction_icon_id}', 'PostController@addReaction')->name('add_reaction');
  Route::get('/remove_reaction/{user_id}/{post_id}/{reaction_icon_id}', 'PostController@removeReaction')->name('remove_reaction');
  Route::get('/select_reaction/{user_id}/{post_id}/{reaction_icon_id}', 'PostController@selectReaction')->name('select_reaction');
  Route::post('/select_reaction', 'PostController@selectReaction')->name('select_reaction');


  // プロフィール画面
  Route::get('/profile/{user_id}', 'ProfileController@show')->name('profile_show');
  Route::get('/profile/{user_id}/edit', 'ProfileController@show')->name('profile_edit');
  Route::get('/profile/{user_id}/reacted', 'ProfileController@show_reacted')->name('profile_show_reacted');

  // プロフィール設定
  Route::get('/config/index', 'ConfigController@index')->name('config.index');
  Route::post('/config/store', 'ConfigController@store')->name('config.store');

  // ajax
  Route::post('/ajax', 'PostController@ajaxReaction');

});

// API
Route::get('/api/post','ApiController@getPost')
    ->name('api_post');
Route::get('/api/json', 'ApiController@getJson')
    ->name('api_json');

// 勉強用コントローラ
Route::get('/study/monolog', 'StudyController@useMonolog')
    ->name('study_monolog');
Route::get('/study/transaction', 'StudyController@useTransaction')
    ->name('study_transaction');
Route::get('/study/not_use_transaction', 'StudyController@notUseTransaction')
    ->name('study_not_use_transaction');
Route::get('study/download/post/{user_id}', 'StudyController@downloadPostCsv')
    ->name('study_download_post_csv');
Route::get('study/job/write_log', 'StudyController@DispatchWriteLogJob')
    ->name('study_job_write_log');
Route::get('study/scope/local', 'StudyController@studyLocalScope')
    ->name('study_scope_local');
Route::get('study/accessor', 'StudyController@studyAccessor')
    ->name('study_accessor');
Route::get('study/mutator', 'StudyController@studyMutator')
    ->name('study_mutator');
Route::get('study/iframely', 'StudyController@testIframely')
    ->name('study_iframely');
Route::get('study/import_csv/index', 'StudyController@importPostByCsvIndex')
    ->name('study_import_csv_index');
Route::post('study/import_csv/store', 'StudyController@importPostByCsvStore')
    ->name('study_import_csv_store');