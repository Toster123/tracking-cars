<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\APIController as APIController;
use Illuminate\Http\Request;

use App\Group;
use App\Car;

class GroupsController extends APIController
{
    public function groups (Request $request) {
        $conditions = [
            ['title', '=', $request->name],
        ];

        if ($this->user->role == $this->roles['admin']) {
            $groups = Group::where($this->filterConditions($conditions));
        } elseif ($this->user->role == $this->roles['user']) {
            $groups = Group::where('user_id', $this->user->_id)->where($this->filterConditions($conditions));
        } else {
            return $this->sendError('Неизвестная роль', 403);
        }
        $groups = $this->getQuery($groups, $request->count);

        $cars = [];

        if ((int)$request->withCars) {
            $cars = ['cars' => []];
            foreach ($groups as $group) {
                $groupCars = Car::where('group_id', $group->_id);

                $cars['cars'][$group->_id] = $this->getQuery($groupCars, false);
                if ((int)$request->withLatestData) {
                    $cars['cars'][$group->_id] = $this->getCarsLatestData($cars['cars'][$group->_id]);
                }
            }
        }

        return $this->sendResponse($groups, $cars);
    }

    public function group (Request $request, $group_id) {
        $group = Group::find($group_id);
        if (!$group) {
            return $this->sendError('Группа не найдена', 404);
        }

        if ($this->user->role !== $this->roles['admin']) {
            if ($this->user->role !== $this->roles['user']) {
                return $this->sendError('Неизвестная роль', 403);
            }
            if ($group->user_id !== $this->user->_id) {
                return $this->sendError('Недостаточно прав', 403);
            }
        }

        return $this->sendResponse($group);
    }

    public function cars (Request $request, $group_id) {
        $group = Group::find($group_id);
        if (!$group) {
            return $this->sendError('Группа не найдена', 404);
        }

        if ($this->user->role !== $this->roles['admin']) {
            if ($this->user->role !== $this->roles['user']) {
                return $this->sendError('Неизвестная роль', 403);
            }
            if ($group->user_id !== $this->user->_id) {
                return $this->sendError('Недостаточно прав', 403);
            }
        }

        $cars = $this->getQuery(Car::where('group_id', $group_id), $request->count);

        if ((int)$request->withLatestData) {
            $cars = $this->getCarsLatestData($cars);
        }

        return $this->sendResponse($cars);
    }
}
