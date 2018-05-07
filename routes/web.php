<?php

Route::get('/', function () {
    return redirect('home');
})->middleware('auth');

Auth::routes();

/*
 * Rutas que requieren autenticación
 */
Route::middleware(['auth', 'not-admin'])->group(function () {
    Route::get('/home', function () {
        return redirect('dinamicas');
    })->name('home');

    Route::resource('dinamicas', 'DinamicaController');
    Route::post('/dinamicas/action', 'DinamicaController@action');
    Route::resource('users', 'UsersController',
        ['except' => ['show', 'edit', 'update', 'destroy']]);

    Route::get('/kpi', 'KpiController@index')->name('kpi');

    Route::get('/zonas', 'MunicipiosController@show');
    Route::post('/zonas/action', 'MunicipiosController@action');
    Route::get('/zonas/create', 'MunicipiosController@create');
    Route::get('/zonas/{zona_id}', 'MunicipiosController@edit');

    Route::get('/venues', 'VenuesController@show');
    Route::post('/venues/action', 'VenuesController@action');
    Route::get('/venues/create', 'VenuesController@create');
    Route::get('/venues/{zona_id}', 'VenuesController@edit');
});

/*
 * Rutas de Gestión de tickets
 */
Route::middleware(['admin'])->group(function () {
    Route::get('/admin', 'UsersController@adminView')->name('admin');
    Route::get('/admin/recibo/{recibo_id}', 'RecibosController@show');
    Route::post('/admin/recibo/{recibo_id}', 'RecibosController@update');
});