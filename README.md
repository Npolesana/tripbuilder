# Tripbuilder is an API to retrieve flights and build trips.

Stack used:
 - Laravel 8
 - PHP 7.4
 - PhpStorm as editor

External libraries used:
 - Laravel Fractal 

Documentation is hosted at https://app.apiary.io/flights22

The API endpoints are hosted at https://www.polesana.it/api/v1

The whole project can be tested at the above address endpoints.

The database used is included in the root folder of the project.

To install this locally, you will need to pull the repository and use your own .env file (you can rename the .env.example and use your settings there).
You can import the database (flighthub_2024-03.31.sql) or use the migrations and the seeders alternatively.


# Worflow

A Flight consists of an airline, a departure airport, an arrival aiport, departure time and date and arrival time and date.

Api is allowing user to search a flight using a departing airport, a departure date, an arrival airport and arrival date.
 - Results are paginated and grouped by departure and arrival flights
 - Optionally results can sorted by price and arrival time
 - Optionally can be a one-way.
 - Can optionally include airports close by. This is done by adding a range of +- 1 to both latitude and longitude, the radious is about ~110 Kms.
 - Can optionally filter by airline.


Once the results are displayed, another API call can be made to build a trip, the only parameters needed are the respective flight ids.

This Api calls supports open jaw trips by simply using two different flight ids from two one-way trips.

The same principle is applied to multi city trips, just adding multi_city_trip_1, multi_city_trip_2 or multi_city_trip_3 (on top of departure and arrival ids.)

Provided there is a postman collection called Flight Builder.postman_collection.json with the list of available calls.












