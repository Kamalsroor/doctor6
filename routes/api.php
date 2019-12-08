<?php
Route::post('clinte_rgistar',  'Api\V1\Admin\ClientsApiController@store');
Route::post('partner_rgistar',  'Api\V1\Admin\PartnersApiController@store');
Route::post('clinte_login',  'Api\V1\Admin\ClientsApiController@login');
Route::post('partner_login',  'Api\V1\Admin\PartnersApiController@login');
// Specialties
Route::apiResource('specialties', 'Api\V1\Admin\SpecialtiesApiController');
Route::group(['prefix' => 'doctor', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:partner']], function () {
    Route::post('workday', 'PartnersApiController@Workday');
    Route::put('not-active/{id}', 'PartnersApiController@NotActive');
    Route::get('workdays', 'PartnersApiController@WorkDays');
    Route::get('workdays/{id}', 'PartnersApiController@WorkDayTime');
    Route::post('workdays/done', 'PartnersApiController@WorkDaysClientDone');
    // Route::post('partners/media', 'PartnersApiController@storeMedia')->name('partners.storeMedia');
    Route::apiResource('partners', 'PartnersApiController');
});

Route::group(['prefix' => 'client', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:client']], function () {
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
    Route::post('worktime', 'PartnersApiController@WorkDaysClient');


    // Clients
    Route::post('clients/media', 'ClientsApiController@storeMedia')->name('clients.storeMedia');
    Route::apiResource('clients', 'ClientsApiController');
});
