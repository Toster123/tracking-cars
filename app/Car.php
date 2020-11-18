<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use App\Moving;
use App\Event;
use App\Group;
use App\Trip;

class Car extends Model
{
    protected $collection = 'cars';
    protected $fillable = ['title', 'imei'];


    public function movings () {
        return $this->hasMany(Moving::class);
    }
    public function events () {
        return $this->hasMany(Event::class);
    }

    public function trips () {
        return $this->hasMany(Trip::class);
    }
    public function lastCoordinates () {
        $movings = Moving::where('car_id', $this->_id);
        return $movings->where('created_at', $movings->max('created_at'))->first();
    }
    public function group () {
        return $this->belongsTo(Group::class);
    }
}
