<?php

Route::get('dinamicas/{brand_id?}', 'DinamicaController@api');
Route::post('dinamicas', 'DinamicaController@store');
Route::get('venues/search/{q}', 'VenuesController@search');
Route::get('municipios/search/{q}', 'MunicipiosController@search');
Route::get('marcas/search/{q}', 'MarcasController@search');
Route::get('users/{brand_id?}', 'UsersController@api');
Route::get('brands', 'BrandsController@api');
Route::get('notificaciones/{id}/read', 'NotificacionController@read');
Route::get('notificaciones/{user_id}/{status?}', 'NotificacionController@api');
Route::get('ranking/{marca_id}', 'PuntosController@topByMarca');