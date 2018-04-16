<?php

Route::get('dinamicas/{brand_id?}', 'DinamicaController@api');
Route::post('dinamicas', 'DinamicaController@store');
Route::get('venues/search/{q}', 'VenuesController@search');
Route::get('venues', 'VenuesController@index');
Route::get('municipios/search/{q}', 'MunicipiosController@search');
Route::get('municipios', 'MunicipiosController@index');
Route::get('marcas/search/{q}', 'MarcasController@search');
Route::get('marcas/{brand_id?}', 'MarcasController@marcas');
Route::get('marcas', 'MarcasController@index');
Route::get('users/{brand_id?}', 'UsersController@api');
Route::get('brands', 'BrandsController@api');
Route::get('notificaciones/{id}/all', 'NotificacionController@allRead');
Route::get('notificaciones/{id}/read', 'NotificacionController@read');
Route::get('notificaciones/{user_id}/{status?}', 'NotificacionController@api');
Route::get('ranking/{marca_id}', 'PuntosController@topByMarca');
Route::get('tickets', 'RecibosController@api');
Route::get('destilados', 'DestiladoController@index');
Route::post('kpi', 'KpiController@getMarcaData');
Route::post('kpi/centro', 'KpiController@getCentroData');