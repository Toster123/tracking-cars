<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use App\Car;
use App\User;

class Event extends Model
{
    protected $collection = 'events';
    protected $fillable = ['type', 'title'];

    public function car () {
        return $this->belongsTo(Car::class);
    }
    public function user () {
        return $this->belongsTo(User::class);
    }
}
