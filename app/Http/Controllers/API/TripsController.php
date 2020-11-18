<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\APIController as APIController;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Trip;
use App\Car;
use App\Moving;

class TripsController extends APIController
{
    public function trips (Request $request) {
        $conditions = [
            ['car_id', '=', $request->car_id],
            ['created_at', '>=', $request->from ? new Carbon($request->from) : null],
            ['created_at', '<=', $request->upTo ? new Carbon($request->upTo) : null],
        ];

        if ($this->user->role == $this->roles['admin']) {
            $trips = Trip::where($this->filterConditions($conditions));
        } elseif ($this->user->role == $this->roles['user']) {
            $carsIds = Car::where('user_id', $this->user->_id)->pluck('_id');
            $trips = Trip::whereIn('car_id', $carsIds)->where($this->filterConditions($conditions));
        } else {
            return $this->sendError('Неизвестная роль', 403);
        }
        $trips = $this->getQuery($trips, $request->count, $request->orderByAsc);

        $movings = [];

        if ((int)$request->withMovings) {
            $movings = ['movings' => []];

            $conditions = $this->filterConditions([
                ['created_at', '>=', $request->from ? new Carbon($request->from) : null],
                ['created_at', '<=', $request->upTo ? new Carbon($request->upTo) : null],
            ]);
            foreach ($trips as $trip) {
                $tripMovings = Moving::where('trip_id', $trip->_id)->where($conditions);

                $movings['movings'][$trip->_id] = $this->getQuery($tripMovings, false, $request->orderMovingsByAsc, ['lat', 'lng', 'speed', 'course', 'created_at']);
            }
        }

        return $this->sendResponse($trips, $movings);
    }

    public function trip ($trip_id) {
        $trip = Trip::find($trip_id);
        if (!$trip) {
            return $this->sendError('Поездка не найдена', 404);
        }

        if ($this->user->role !== $this->roles['admin']) {
            if ($this->user->role !== $this->roles['user']) {
                return $this->sendError('Неизвестная роль', 403);
            }
            $car = Car::find($trip->car_id);
            if (!$car || $car->user_id !== $this->user->_id) {
                return $this->sendError('Недостаточно прав', 403);
            }
        }

        return $this->sendResponse($trip);
    }

    public function movings (Request $request, $trip_id) {
        $trip = Trip::find($trip_id);
        if (!$trip) {
            return $this->sendError('Поездка не найдена', 404);
        }

        if ($this->user->role !== $this->roles['admin']) {
            if ($this->user->role !== $this->roles['user']) {
                return $this->sendError('Неизвестная роль', 403);
            }
            $car = Car::find($trip->car_id);
            if (!$car || $car->user_id !== $this->user->_id) {
                return $this->sendError('Недостаточно прав', 403);
            }
        }

        $conditions = [
            ['created_at', '>', $request->from ? new Carbon($request->from) : null],
            ['created_at', '<', $request->from ? new Carbon($request->upTo) : null],
        ];

        $movings = Moving::where('trip_id', $trip_id)->where($this->filterConditions($conditions));

        return $this->sendResponse($this->getQuery($movings, $request->count, $request->orderByAsc, ['lat', 'lng', 'speed', 'course', 'created_at']));
    }
}
