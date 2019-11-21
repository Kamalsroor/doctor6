<?php
Route::post('clinte_rgistar',  'Api\V1\Admin\ClientsApiController@store');
Route::post('partner_rgistar',  'Api\V1\Admin\PartnersApiController@store');
Route::post('clinte_login',  'Api\V1\Admin\ClientsApiController@login');
Route::post('partner_login',  'Api\V1\Admin\PartnersApiController@login');



Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:api']], function () {
    // Permissions
    Route::apiResource('permissions', 'PermissionsApiController');

    // Roles
    Route::apiResource('roles', 'RolesApiController');

    // Users
    Route::apiResource('users', 'UsersApiController');

    // Partners
    Route::post('partners/media', 'PartnersApiController@storeMedia')->name('partners.storeMedia');
    Route::apiResource('partners', 'PartnersApiController');

    // Specialties
    Route::apiResource('specialties', 'SpecialtiesApiController');

    // Pharmacies
    Route::post('pharmacies/media', 'PharmacyApiController@storeMedia')->name('pharmacies.storeMedia');
    Route::apiResource('pharmacies', 'PharmacyApiController');

    // Clients
    Route::post('clients/media', 'ClientsApiController@storeMedia')->name('clients.storeMedia');
    Route::apiResource('clients', 'ClientsApiController');
});
