<?php

Route::get('/', function () {
    return redirect('home');
})->middleware('auth');

Auth::routes();
//Route::get('/login', 'Auth\LoginController@login')->middleware(['web', 'guest']);

Route::get('/home', function () {
    return redirect('dinamicas');
})->name('home')->middleware('auth');

Route::resource('dinamicas', 'DinamicaController')->middleware('auth');
Route::post('/dinamicas/action', 'DinamicaController@action')->middleware('auth');
Route::resource('users', 'UsersController')->middleware('auth');

Route::get('/admin', 'UsersController@adminView')->name('admin');
Route::get('/admin/recibo/{recibo_id}', 'RecibosController@show');
Route::post('/admin/recibo/{recibo_id}', 'RecibosController@update');