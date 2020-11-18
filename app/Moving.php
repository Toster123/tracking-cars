<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Moving extends Model
{
    protected $collection = 'movings';
    protected $fillable = ['imei', 'lat', 'lng'];
}
