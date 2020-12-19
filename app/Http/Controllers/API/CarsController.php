<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\APIController as APIController;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Car;
use App\Event;
use App\Trip;
use App\Moving;

class CarsController extends APIController
{
    public function cars (Request $request) {
        $conditions = [
            ['group_id', '=', $request->group_id],
            ['title', '=', $request->name],
        ];

        if ($this->user->role == $this->roles['admin']) {
            $cars = Car::where($this->filterConditions($conditions));
        } elseif ($this->user->role == $this->roles['user']) {
            $cars = Car::where('user_id', $this->user->_id)->where($this->filterConditions($conditions));
        } else {
            return $this->sendError('Неизвестная роль', 403);
        }

        $cars = $this->getQuery($cars, $request->count);

        if ((int)$request->withLatestData) {
            $cars = $this->getCarsLatestData($cars);
        }
        return $this->sendResponse($cars);
    }

    public function car (Request $request, $car_id) {
        $car = Car::find($car_id); 
		$car->parking = 60;
        if (!$car) {
            return $this->sendError('Машина не найдена', 404);
        }

        if ($this->user->role !== $this->roles['admin']) {
            if ($this->user->role !== $this->roles['user']) {
                return $this->sendError('Неизвестная роль', 403);
            }
            if ($car->user_id !== $this->user->_id) {
                return $this->sendError('Недостаточно прав', 403);
            }
        }

        if ((int)$request->withLatestData) {
            $car = $this->getCarsLatestData([$car])[0];
        }
		
		$car->status = 1;
		
        return $this->sendResponse($car);
    }

    public function events (Request $request, $car_id) {
        $car = Car::find($car_id);
        if (!$car) {
            return $this->sendError('Машина не найдена', 404);
        }

        if ($this->user->role !== $this->roles['admin']) {
            if ($this->user->role !== $this->roles['user']) {
                return $this->sendError('Неизвестная роль', 403);
            }
            if ($car->user_id !== $this->user->_id) {
                return $this->sendError('Недостаточно прав', 403);
            }
        }

        $conditions = [
            ['created_at', '>=', $request->from ? new Carbon($request->from) : null],
            ['created_at', '<=', $request->upTo ? new Carbon($request->upTo) : null],
        ];

        $events = Event::where('car_id', $car_id)->where($this->filterConditions($conditions));

        return $this->sendResponse($this->getQuery($events, $request->count, $request->orderByAsc));
    }

    public function eventsMore (Request $request, $car_id) {
        $car = Car::find($car_id);
        if (!$car) {
            return $this->sendError('Машина не найдена', 404);
        }

        if ($this->user->role !== $this->roles['admin']) {
            if ($this->user->role !== $this->roles['user']) {
                return $this->sendError('Неизвестная роль', 403);
            }
            if ($car->user_id !== $this->user->_id) {
                return $this->sendError('Недостаточно прав', 403);
            }
        }

        if ($request->event_id ||
        $request->from ||
        $request->upTo) {
            $event = Event::find($request->event_id);
            if (!$event && !($request->from || $request->upTo)) {
                    return $this->sendError('Событие не найдено', 404);
            }
            $conditions = [
                ['created_at', (bool)$request->orderByAsc ? '>=' : '<=', $event->created_at ? new Carbon($event->created_at) : null],
                ['created_at', '>=', $request->from ? new Carbon($request->from) : null],
                ['created_at', '<=', $request->upTo ? new Carbon($request->upTo) : null],
            ];

            $events = Event::where('car_id', $car_id)->where($this->filterConditions($conditions));

            return $this->sendResponse($this->getQuery($events, $request->count, $request->orderByAsc));
        } else {
            return $this->sendError('Отсутствуют обязательные поля', 400);
        }
    }

    public function trips (Request $request, $car_id) {
        $car = Car::find($car_id);
        if (!$car) {
            return $this->sendError('Машина не найдена', 404);
        }

        if ($this->user->role !== $this->roles['admin']) {
            if ($this->user->role !== $this->roles['user']) {
                return $this->sendError('Неизвестная роль', 403);
            }
            if ($car->user_id !== $this->user->_id) {
                return $this->sendError('Недостаточно прав', 403);
            }
        }

        $conditions = [
            ['created_at', '<=', $request->upTo ? new Carbon($request->upTo) : null],
        ];

        $trips = Trip::where('car_id', $car->id)->where($this->filterConditions($conditions));
        if ($request->from) {
            $trips->where(function ($q) use($request) {
                return $q->where('ended_at', '>=', new Carbon($request->from))->orWhere('ended_at', null);
            });
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

    public function movings (Request $request, $car_id) {
        $car = Car::find($car_id);
        if (!$car) {
            return $this->sendError('Машина не найдена', 404);
        }

        if ($this->user->role !== $this->roles['admin']) {
            if ($this->user->role !== $this->roles['user']) {
                return $this->sendError('Неизвестная роль', 403);
            }
            if ($car->user_id !== $this->user->_id) {
                return $this->sendError('Недостаточно прав', 403);
            }
        }

        $conditions = [
            ['created_at', '>', $request->from ? new Carbon($request->from) : null],
            ['created_at', '<', $request->from ? new Carbon($request->upTo) : null],
        ];

        $movings = Moving::where('car_id', $car_id)->where($this->filterConditions($conditions));

        return $this->sendResponse($this->getQuery($movings, $request->count, $request->orderByAsc, ['lat', 'lng', 'speed', 'course', 'created_at']));
    }
}
