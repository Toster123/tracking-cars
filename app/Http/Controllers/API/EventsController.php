<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\APIController as APIController;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Event;
use App\Car;

class EventsController extends APIController
{
    public function events (Request $request) {
        $conditions = [
            ['created_at', '>=', $request->from ? new Carbon($request->from) : null],
            ['created_at', '<=', $request->upTo ? new Carbon($request->upTo) : null],
        ];

        if ($this->user->role == $this->roles['admin']) {
            $events = Event::where($this->filterConditions($conditions));
        } elseif ($this->user->role == $this->roles['user']) {
            $carsIds = Car::where('user_id', $this->user->_id)->pluck('_id');
            $events = Event::whereIn('car_id', $carsIds)->where($this->filterConditions($conditions));
        } else {
            return $this->sendError('Неизвестная роль', 403);
        }

        return $this->sendResponse($this->getQuery($events, $request->count, $request->orderByAsc));
    }



    public function eventsMore (Request $request) {
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

            if ($this->user->role == $this->roles['admin']) {
                $events = Event::where($this->filterConditions($conditions));
            } elseif ($this->user->role == $this->roles['user']) {
                $carsIds = Car::where('user_id', $this->user->_id)->pluck('_id');
                $events = Event::whereIn('car_id', $carsIds)->where($this->filterConditions($conditions));
            } else {
                return $this->sendError('Неизвестная роль', 403);
            }

            return $this->sendResponse($this->getQuery($events, $request->count, $request->orderByAsc));
        } else {
            return $this->sendError('Отсутствуют обязательные поля', 400);
        }
    }

    public function event ($event_id) {
    $event = Event::find($event_id);
    if (!$event) {
        return $this->sendError('Событие не найдено', 404);
    }

    if ($this->user->role !== $this->roles['admin']) {
        if ($this->user->role !== $this->roles['user']) {
            return $this->sendError('Неизвестная роль', 403);
        }
        $car = Car::find($event->car_id);
        if (!$car || $car->user_id !== $this->user->_id) {
            return $this->sendError('Недостаточно прав', 403);
        }
    }

    return $this->sendResponse($event);
    }
}
