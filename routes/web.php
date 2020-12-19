<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Car;
use App\Moving;
use App\Trip;
use App\User;
use App\Group;
Route::get('/', function () {
    dd(now());
    $t = Trip::find('5f983f4abe07b76f98b329d8');
dd($t->getMileage());
    $acars = \Illuminate\Support\Facades\Auth::user()->cars()->get();
    $cars = Car::find('5f42938f1e5f6e01301334a8');
    return view('auth.verify');
    //$car = Car::create(['title' => 'Mercedes', 'imei' => '821325362', ]);
    //Moving::where('imei', '821325362')->delete();
    //$mov = Car::find('5f562869f565fa7b2e420cb7')->movings()->create(['imei' => '821325362', 'lat' => 43.335474, 'lng' => 76.938624 ]);
    return view('welcome', compact('cars'));
})->name('home');

Auth::routes([
    'confirm' => false,
    'verify' => false
]);

Route::group(['prefix' => 'verify', 'middleware' => 'guest'], function () {
    Route::get('/', 'Auth\VerificationController@verify')->name('verify');
    Route::get('/{token}', 'Auth\VerificationController@verifyToken')->name('verify.token');
});


Route::get('/logout', 'Auth\LoginController@logout')->name('get:logout');


Route::group(['prefix' => 'monitoring', 'middleware' => 'auth'], function () {
    Route::get('/', 'MonitoringController@monitoring')->name('monitoring');


    Route::group(['prefix' => 'settings'], function () {
        Route::get('/cars', 'SettingsController@cars')->name('settings.cars');


        Route::group(['prefix' => 'cars'], function () {
            Route::post('/create', 'SettingsController@createCar')->name('settings.create.car');
            Route::post('/edit', 'SettingsController@editCars')->name('settings.edit.cars');
            Route::post('/delete', 'SettingsController@deleteCars')->name('settings.delete.cars');
            Route::post('/{car}/edit', 'SettingsController@editCar')->name('settings.edit.car');
            Route::post('/{car}/delete', 'SettingsController@deleteCar')->name('settings.delete.car');
        });

        Route::group(['prefix' => 'groups'], function () {
            Route::post('/create', 'SettingsController@createGroup')->name('settings.create.group');
            Route::post('/{group}/edit', 'SettingsController@editGroup')->name('settings.edit.group');
            Route::post('/{group}/delete', 'SettingsController@deleteGroup')->name('settings.delete.group');
        });

        });

    Route::group(['prefix' => 'cars'], function () {
        Route::post('/{car_id}/trips', 'CarsController@trips')->name('car.trips');
    });

    Route::group(['prefix' => 'trips'], function () {
        Route::post('/{trip_id}/moving', 'CarsController@tripMovingByCoordinates')->name('trip.moving.bycoordinates');
    });


    //ajax
    Route::group(['prefix' => 'cars'], function () {
        Route::group(['prefix' => '{car_id}'], function () {
            Route::get('/', 'CarsController@car')->name('car');

            Route::post('/events/more', 'CarsController@eventsMore')->name('car.events.more');
        });
    });
    Route::post('/events/more', 'EventsController@more')->name('events.more');
    Route::get('/settings/cars/search', 'SettingsController@carsSearch')->name('settings.cars.search');
});

use App\Http\Controllers\UserController as UserController;
use Illuminate\Support\Facades\Auth;

Route::middleware('auth:,api')->get('/getRoom', function () {
    $UserController = new UserController();

    return response()->json([
        'room' => $UserController->user->role == $UserController->roles['admin'] ? 'admin' : $UserController->user->_id,
    ]);
});
