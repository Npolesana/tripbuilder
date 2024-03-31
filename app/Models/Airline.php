<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Airline
 *
 *
 * @property int           id
 * @property string        code
 * @property string        name
 * @property Carbon        created_at
 * @property Carbon        updated_at
 * @property Carbon        deleted_at
 */


class Airline extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'airlines';
    protected $guarded = [];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];




}
