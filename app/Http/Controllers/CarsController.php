<?php

namespace App\Http\Controllers;

use App\Http\Controllers\UserController as UserController;
use Illuminate\Http\Request;

use App\Car;
use App\Event;
use App\Trip;
use App\Moving;

use Carbon\Carbon;

class CarsController extends UserController
{
    public function car ($car_id) {
        $car = Car::find($car_id);
        if ($this->user->role == $this->roles['admin'] || $car->user_id == $this->user->_id) {
            $events = Event::where('car_id', $car->_id)->orderBy('created_at', 'DESC')->limit(12)->get();

            $movings = Moving::where('car_id', $car->_id);
            $moving = $movings->where('created_at', $movings->max('created_at'))->first();

            $car->setAttribute('latest_moving', $moving);

            $car->setAttribute('latest_event', !empty($events->toArray()) ? $events[0] : null);

            return json_encode([
                'car' => $car,
                'events' => view('layouts.monitoring.events', compact('events'))->render(),
                'panel' => view('layouts.monitoring.cars.panel', compact('car'))->render(),
                'mobile_panel' => view('layouts.mobile.monitoring.cars.panel', compact('car'))->render(),
                'calendar' => view('layouts.monitoring.calendar', compact('car'))->render(),
            ]);
        }
    }

    public function eventsMore (Request $request, $car_id) {
        $car = Car::find($car_id);
        if (($this->user->role == $this->roles['admin'] || $car->user_id == $this->user->_id) && $request->id) {

                $event = Event::find($request->id);

                $query = Event::where('car_id', $car->_id)->where('created_at', '<', $event->created_at);

                $count = $query->count();

                $events = $query->orderBy('created_at', 'DESC')->limit(12)->get();
                if ($count <= 12) {
                    $events->last()->setAttribute('isLast', true);
                }

                return view('layouts.monitoring.events', compact('events'));
        }
    }

    public function trips ($car_id, Request $request) {
        $car = Car::find($car_id);
        if ($car && $request->fromDate && $request->fromTime &&
            ((($request->upToDate && $request->upToTime) || $request->displayUpToCurrentTime) ||
                (($request->fromDate == $request->upToDate && $request->fromTime <= $request->upToTime) || $request->fromDate < $request->upToDate)) &&
            ($this->user->role == $this->roles['admin'] || ($this->user->role == $this->roles['user'] && $car->user_id == $this->user->_id))) {

            $carTitle = $car->title;


            $trips = Trip::where('car_id', $car->_id)->where(function ($q) use($request) {
                return $q->where('ended_at', '>', new Carbon($request->fromDate . ' ' . $request->fromTime))->orWhere('ended_at', null);
            });

            if (!$request->displayUpToCurrentTime) {
                $trips = $trips->where('created_at', '<', new Carbon($request->upToDate . ' ' . $request->upToTime));
            }
            $trips = $trips->orderBy('created_at', 'DESC')->get();

            $result = [];

            foreach ($trips as $trip) {
                $movings = Moving::where('trip_id', $trip->_id)->where('created_at', '>', new Carbon($request->fromDate . ' ' . $request->fromTime));

                if (!$request->displayUpToCurrentTime) {
                    $movings = $movings->where('created_at', '<', new Carbon($request->upToDate . ' ' . $request->upToTime));
                }
                $movings = $movings->get(['lat', 'lng', 'speed']);

                $result[$trip->_id] = $movings;
            }

            return json_encode([
                'path' => $result,
                'view' => view('layouts.monitoring.trips', compact('carTitle', 'trips'))->render(),
            ]);

        } else {
            return response()->json(false);
        }
    }

    public function tripMovingByCoordinates (Request $request, $trip_id) {
        if ($request->lat && $request->lng) {
            return response()->json(Moving::where('trip_id', $trip_id)->where('lat', (float)$request->lat)->where('lng', (float)$request->lng)->first());
        }
    }
}
