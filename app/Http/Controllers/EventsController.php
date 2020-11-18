<?php

namespace App\Http\Controllers;

use App\Http\Controllers\UserController as UserController;
use Illuminate\Http\Request;

use App\Car;
use App\Event;

class EventsController extends UserController
{
    public function more (Request $request) {
        if ($request->id) {
            $event = Event::find($request->id);
            if ($this->user->role == $this->roles['admin']) {
                $query = Event::where('created_at', '<', $event->created_at);
            } elseif ($this->user->role == $this->roles['user']) {
                $carsIds = Car::where('user_id', $this->user->_id)->pluck('_id');
                $query = Event::whereIn('car_id', $carsIds)->where('created_at', '<', $event->created_at);
            }
            $count = $query->count();

            $events = $query->orderBy('created_at', 'DESC')->limit(12)->get();
            if ($count <= 12) {
                $events->last()->setAttribute('isLast', true);
            }


            return view('layouts.monitoring.events', compact('events'));
        }
    }
}
