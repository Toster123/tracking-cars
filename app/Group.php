<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;
use App\Car;

class Group extends Model
{
    protected $collection = 'groups';
    protected $fillable = ['title', 'color'];

    public function cars () {
        return $this->hasMany(Car::class);
    }
}
