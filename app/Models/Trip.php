<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Trip
 *
 *
 * @property int           id
 * @property int           flight_id
 * @property Carbon        departure_date
 * @property Collection    flights
 * @property float         total_price
 * @property Carbon        created_at
 * @property Carbon        updated_at
 * @property Carbon        deleted_at
 */


class Trip extends Model
{
     use HasFactory;

    protected $table = 'trips';
    protected $guarded = [];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];


    public function flights(): BelongsToMany
    {
        return $this->belongsToMany(Flight::class, 'trip_flights', 'trip_id', 'flight_id');
    }

}
