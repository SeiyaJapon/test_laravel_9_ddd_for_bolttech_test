<?php

use DDD\Domain\ValueObject\User\UserOldId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::fallback(function(){
    return response()->json(
        [
            'message' => 'API resource not found'
        ],
        404
    );
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('\DDD\Infrastructure\UI\API\Controllers')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Auth
    |--------------------------------------------------------------------------
    */
    Route::prefix('auth')->group(function () {
        /*
        |--------------------------------------------------------------------------
        | No need bearer
        |--------------------------------------------------------------------------
        */

        Route::post('login', 'AuthenticationController@login')->name('login');

        Route::post('register', 'AuthenticationController@register');

        Route::post('register_admin', 'AuthenticationController@registerAdmin');

        /*
        |--------------------------------------------------------------------------
        | Need bearer
        |--------------------------------------------------------------------------
        */

        Route::middleware(['auth:api', 'has.permissions'])->group(function () {
            Route::post('logout', 'AuthenticationController@logout');
        });
    });

    Route::middleware(['auth:api'])->group(function () {
        /*
        |--------------------------------------------------------------------------
        | Car
        |--------------------------------------------------------------------------
        */
        Route::prefix('car')->group(function () {
            Route::get('{id}/id', 'CarController@getAvailableCarsNow');

            Route::get('{start}/{end}/available', 'CarController@getAvailableCarsByDates');
        });

        /*
        |--------------------------------------------------------------------------
        | Booking
        |--------------------------------------------------------------------------
        */
        Route::prefix('booking')->group(function () {
            Route::post('car', 'BookingController@bookingCar');
        });
    });
});
