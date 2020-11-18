<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::group(['middleware' => 'guest:api'], function () {
    Route::post('/login', 'API\Auth\AuthController@login');
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', 'API\Auth\AuthController@logout');

    Route::group(['prefix' => 'cars'], function () {

        Route::get('/', 'API\CarsController@cars');

        Route::group(['prefix' => '{car_id}'], function () {
            Route::get('/', 'API\CarsController@car');

            Route::get('/events', 'API\CarsController@events');

            Route::get('/events/more', 'API\CarsController@eventsMore');

            Route::get('/trips', 'API\CarsController@trips');

            Route::get('/movings', 'API\CarsController@movings');
        });
    });

    Route::group(['prefix' => 'groups'], function () {
        Route::get('/', 'API\GroupsController@groups');

        Route::group(['prefix' => '{group_id}'], function () {
            Route::get('/', 'API\GroupsController@group');

            Route::get('/cars', 'API\GroupsController@cars');
        });
    });

    Route::group(['prefix' => 'events'], function () {
            Route::get('/', 'API\EventsController@events');

            Route::get('/more', 'API\EventsController@eventsMore');

            Route::get('/{event_id}', 'API\EventsController@event');
    });



    Route::group(['prefix' => 'trips'], function () {
        Route::get('/', 'API\TripsController@trips');

        Route::group(['prefix' => '{trip_id}'], function () {
            Route::get('/', 'API\TripsController@trip');

            Route::get('/movings', 'API\TripsController@movings');
        });
    });
});

