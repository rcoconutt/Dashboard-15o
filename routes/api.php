<?php
Route::get('dinamicas', 'DinamicaController@api');
Route::post('dinamicas', 'DinamicaController@store');
Route::get('venues/search/{q}', 'VenuesController@search');
Route::get('municipios/search/{q}', 'MunicipiosController@search');
Route::get('marcas/search/{q}', 'MarcasController@search');