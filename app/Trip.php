<?php

namespace App;


use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

use App\Car;
use App\Moving;

class Trip extends Model
{
    protected $collection = 'trips';
    protected $fillable = ['ended_at'];

    public function car () {
        return $this->belongsTo(Car::class);
    }
    public function getMileage () {
        return isset($this->mileage) ? $this->mileage : Redis::get('trip:mileage:' . $this->_id) ?: 0;
    }
    public function movings () {
        return $this->hasMany(Moving::class);
    }
}
