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

Route::get('/', function () {
    return redirect('login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/host', 'HomeController@host')->name('host');

Route::get('/database', 'HomeController@database')->name('database');

Route::get('/schema', 'HomeController@schema')->name('schema');

Route::get('/tabla', 'HomeController@tabla')->name('tabla');

Route::get('/export_excel', 'HomeController@export_excel')->name('export_excel');

Route::get('/store', 'HomeController@store')->name('home.store');

Route::get('/destroy/{id}', 'HomeController@destroy')->name('home.destroy');

Route::get('/edit/{id}', 'HomeController@edit')->name('home.edit');

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::get('/ajax_columna','AjaxController@ajax_columna')->name('ajax_columna');

