<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Flight
 *
 *
 * @property int           id
 * @property int           airline_id
 * @property string        flight_number
 * @property int           departure_airport_id
 * @property int           arrival_airport_id
 * @property Carbon        departure_time
 * @property Carbon        arrival_time
 * @property Carbon departure_date
 * @property Carbon arrival_date
 * @property float         price
 * @property Carbon        created_at
 * @property Carbon        updated_at
 * @property Carbon        deleted_at
 *
 * @property-read  Airport departure_airport
 * @property-read  Airport arrival_airport
 * @property-read  Airline airline
 */


class Flight extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'flights';
    protected $guarded = [];
    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'departure_date', 'arrival_date'];


    public function departure_airport()
    {
        return $this->belongsTo(Airport::class, 'departure_airport_id');
    }

    public function arrival_airport()
    {
        return $this->belongsTo(Airport::class, 'arrival_airport_id');
    }

    public function airline()
    {
        return $this->belongsTo(Airline::class, 'airline_id');
    }




}
