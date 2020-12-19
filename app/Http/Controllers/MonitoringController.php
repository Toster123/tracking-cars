<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\UserController as UserController;
use App\Car;
use App\Event;
use App\Group;
use App\Moving;

class MonitoringController extends UserController
{
    public function monitoring () {
		 
        if ($this->user->role == $this->roles['admin']) {
            $cars = Car::where('group_id', null)->with('group')->get(); 
            $groups = Group::with('cars')->get();
            $events = Event::orderBy('created_at', 'DESC')->limit(12)->get();
        } elseif ($this->user->role == $this->roles['user']) {
            $cars = Car::where('user_id', $this->user->_id)->where('group_id', null)->with('group')->get();
            $groups = Group::where('user_id', $this->user->_id)->with('cars')->get();
            $carsIds = [];
            $carsIds = array_merge($carsIds, $cars->pluck('_id')->toArray());
            foreach ($groups as $group) {
                $carsIds = array_merge($carsIds, $group->cars()->pluck('_id')->toArray());
            }
            $events = Event::whereIn('car_id', $carsIds)->orderBy('created_at', 'DESC')->limit(12)->get();
        }
		
		

        return view('monitoring.monitoring', compact('cars', 'groups', 'events'));
    }
}
