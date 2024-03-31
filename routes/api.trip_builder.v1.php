<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([], function ()
{

    //======================================================================
    // Airlines
    //======================================================================

    Route::get('/airlines', 'AirlineController@index');
    Route::get('/airlines/{id}', 'AirlineController@view');


    //======================================================================
    // Airports
    //======================================================================

    Route::get('/airports', 'AirportController@index');
    Route::get('/airports/{id}', 'AirportController@view');


    //======================================================================
    // Flights
    //======================================================================

    Route::get('/flights', 'FlightController@index');
    Route::get('/flights/{id}', 'FlightController@view');


    //======================================================================
    // Trips
    //======================================================================

    Route::post('/trips', 'TripController@create');
    Route::get('/trips/{id}', 'TripController@view');
    Route::get('/trips', 'TripController@index');





});
