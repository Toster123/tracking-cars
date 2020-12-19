<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\UserController as UserController;
use Illuminate\Http\Request;

use App\Moving;
use App\Event;

class APIController extends UserController
{
    public function sendResponse($data, $additionalData = []) {
        $response = $additionalData;
        $response['data'] = $data;

        return response()->json($response);
    }

    public function sendError($error, $code) {
        $response = [
            'error' => $error,
        ];

        return response()->json($response, $code);
    }

    public function getQuery ($query, $count, $orderByAsc = false, $fields = []) {
        $query->orderBy('created_at', (bool)$orderByAsc ? 'ASC' : 'DESC');
        if ((int)$count) {
            $query->limit((int)$count);
        }
        return $query->get($fields);
    }

    public function filterConditions ($conditions) {
        foreach ($conditions as $key => $value) {
            if (!$value[2]) {
                unset($conditions[$key]);
            }

            if ($value[2] == 'nothing') {
                $conditions[$key][2] = null;
            }
        }

        return $conditions;
    }

    public function getCarsLatestData ($cars) {
        foreach ($cars as $car) {
            $movings = Moving::where('car_id', $car->_id);
            $moving = $movings->where('created_at', $movings->max('created_at'))->first();
			$moving->address = 'ул. Ержанова';
			$moving->signal = 80;
            $car->setAttribute('latest_moving', $moving);

            $events = Event::where('car_id', $car->_id);
            $event = $events->where('created_at', $events->max('created_at'))->first();
            $car->setAttribute('latest_event', $event);

            $car->setAttribute('signal', $car->getSignal());

            $car->setAttribute('is_driving', $car->getDrivingStatus());
        }

        return $cars;
    }
}
