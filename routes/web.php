<?php

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Route::get('/migrate', function() {
    Artisan::call('migrate:fresh');
    Artisan::call('db:seed');
    // return what you want
    return 'تم بنجاح';
});

Route::get('/link', function () {
    Artisan::call('storage:link');
    return 'تم بنجاح';
});

Route::get('/passport', function () {
    Artisan::call('passport:install');
    return 'تم بنجاح';
});

Route::get('/mast-migrate', function() {
    Artisan::call('migrate');
    return 'تم بنجاح';
});

Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Partners
    Route::delete('partners/destroy', 'PartnersController@massDestroy')->name('partners.massDestroy');
    Route::post('partners/media', 'PartnersController@storeMedia')->name('partners.storeMedia');
    Route::resource('partners', 'PartnersController');

    // Specialties
    Route::delete('specialties/destroy', 'SpecialtiesController@massDestroy')->name('specialties.massDestroy');
    Route::resource('specialties', 'SpecialtiesController');

    // Pharmacies
    Route::delete('pharmacies/destroy', 'PharmacyController@massDestroy')->name('pharmacies.massDestroy');
    Route::post('pharmacies/media', 'PharmacyController@storeMedia')->name('pharmacies.storeMedia');
    Route::resource('pharmacies', 'PharmacyController');

    // Clients
    Route::delete('clients/destroy', 'ClientsController@massDestroy')->name('clients.massDestroy');
    Route::post('clients/media', 'ClientsController@storeMedia')->name('clients.storeMedia');
    Route::resource('clients', 'ClientsController');
});
