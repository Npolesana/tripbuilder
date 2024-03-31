<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Airport
 *
 *
 * @property int           id
 * @property string        code
 * @property string        city
 * @property string        city_code
 * @property string        country_code
 * @property string        region_code
 * @property float         latitude
 * @property float         longitude
 * @property string        timezone
 * @property Carbon        created_at
 * @property Carbon        updated_at
 * @property Carbon        deleted_at
*/


class Airport extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'airports';
    protected $guarded = [];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];




}
