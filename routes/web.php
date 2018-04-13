<?php

Route::get('/', function () {
    return redirect('home');
})->middleware('auth');

Auth::routes();

Route::middleware(['auth', 'not-admin'])->group(function () {
    Route::get('/home', function () {
        return redirect('dinamicas');
    })->name('home');

    Route::resource('dinamicas', 'DinamicaController');
    Route::post('/dinamicas/action', 'DinamicaController@action');
    Route::resource('users', 'UsersController',
        ['except' => ['show', 'edit', 'update', 'destroy']]);
});

Route::get('/kpi', 'KpiController@index')->name('kpi');

Route::middleware(['admin'])->group(function () {
    Route::get('/admin', 'UsersController@adminView')->name('admin');
    Route::get('/admin/recibo/{recibo_id}', 'RecibosController@show');
    Route::post('/admin/recibo/{recibo_id}', 'RecibosController@update');
});