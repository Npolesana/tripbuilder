<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Trip
 *
 *
 * @property int           id
 * @property int           flight_id
 * @property int           trip_id
 * @property int           segment
 * @property Carbon        created_at
 * @property Carbon        updated_at
 * @property Carbon        deleted_at
 */


class TripFlight extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'trip_flights';
    protected $guarded = [];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];


}
