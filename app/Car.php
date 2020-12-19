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
    public function getSignal () {
        $events = Event::where('car_id', $this->_id)->limit(2)->get();
        if ($events->count() > 1) {
            if ($events[0]->type == 1 || $events[1]->type == 1) {
                return 0;
            }
            return 100;
        }
        return 0;
    }
    public function getDrivingStatus () {
        $events = Event::where('car_id', $this->_id);
        $event = $events->where('created_at', $events->max('created_at'))->first();
        if ($event && ($event->type == 1 || $event->type == 5 || $event->type == 7)) {
            return false;
        }
        return true;
    }
    public function group () {
        return $this->belongsTo(Group::class);
    }
}
