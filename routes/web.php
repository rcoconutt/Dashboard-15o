<?php

Route::get('/', function () {
    return redirect('home');
})->middleware('auth');

Auth::routes();

Route::get('/home', function () {
    return redirect('dinamicas');
})->name('home')->middleware('auth');


Route::resource('dinamicas', 'DinamicaController')->middleware('auth');