<?php

namespace App\Http\Controllers;

use App\Http\Controllers\UserController as UserController;
use Illuminate\Http\Request;

use App\Car;
use App\Group;
use App\Event;
use App\User;


class SettingsController extends UserController
{
    public function cars () {
        $users = [];
        if ($this->user->role == $this->roles['admin']) {
            $cars = Car::where('group_id', null)->with('group')->get();
            $groups = Group::get();
            $events = Event::orderBy('created_at', 'DESC')->limit(12)->get();
            $users = User::get();
        } elseif ($this->user->role == $this->roles['user']) {
            $cars = Car::where('user_id', $this->user->_id)->where('group_id', null)->with('group')->get();
            $groups = Group::where('user_id', $this->user->_id)->with('cars')->get();
            $carsIds = [];
            $carsIds[] = $cars->pluck('_id');
            foreach ($groups as $group) {
                $carsIds[] = $group->cars()->pluck('_id');
            }
            $events = Event::whereIn('car_id', $carsIds)->orderBy('created_at', 'DESC')->limit(12)->get();
        }
        return view('monitoring.settings.cars', compact('cars', 'groups', 'events', 'users'));
    }



    public function createGroup (Request $request) {
        if (!is_null($request->title) && $request->title !== '' &&
            !is_null($request->color) && $request->color !== '') {
            $group = new Group();
            $group->title = $request->title;
            $group->color = $request->color;
            if ($this->user->role == $this->roles['admin']) {
                $group->user_id = null;
            } elseif ($this->user->role == $this->roles['user']) {
                $group->user_id = $this->user->_id;
            }
            $group->save();
        }
        return redirect()->back();
    }

    public function editGroup (Group $group, Request $request) {
        if (!is_null($request->title) && $request->title !== '' &&
            !is_null($request->color) && $request->color !== '') {
            if ($this->user->role == $this->roles['admin'] && !is_null($request->owner)) {
                $group->title = $request->title;
                $group->color = $request->color;
                if ($request->owner == 'nothing') {
                    $group->user_id = null;
                    $cars = Car::where('group_id', $group->_id)->get();
                    foreach ($cars as $car) {
                        $car->user_id = null;
                        $car->save();
                    }
                } elseif (!is_null(User::findOrFail($request->owner))) {
                    $group->user_id = $request->owner;
                    $cars = Car::where('group_id', $group->_id)->get();
                    foreach ($cars as $car) {
                        $car->user_id = $request->owner;
                        $car->save();
                    }
                }
                $group->save();
            } elseif ($this->user->role == $this->roles['user'] && $group->user_id == $this->user->_id) {
                $group->title = $request->title;
                $group->color = $request->color;
                $group->save();
            }
        }
        return redirect()->back();
    }

    public function deleteGroup (Group $group, Request $request) {
            if ($this->user->role == $this->roles['admin']) {
                $cars = Car::where('group_id', $group->_id)->get();
                foreach ($cars as $car) {
                    $car->group_id = null;
                    $car->save();
                }
                $group->delete();
            } elseif ($this->user->role == $this->roles['user'] && $group->user_id == $this->user->_id) {
                $cars = Car::where('group_id', $group->_id)->get();
                foreach ($cars as $car) {
                    $car->group_id = null;
                    $car->save();
                }
                $group->delete();
            }
        return redirect()->back();
    }

    public function createCar (Request $request) {
        if (!is_null($request->title) && $request->title !== '' &&
            !is_null($request->imei) && $request->imei !== '') {
            $car = new Car();
            $car->title = $request->title;
            $car->imei = $request->imei;
            if ($this->user->role == $this->roles['admin']) {
                $car->user_id = null;
            } elseif ($this->user->role == $this->roles['user']) {
                $car->user_id = $this->user->_id;
            }
            $car->save();
        }
        return redirect()->back();
    }


    public function editCar (Car $car, Request $request) {
        if (!is_null($request->title) && $request->title !== '' && !is_null($request->group)) {
            if ($this->user->role == $this->roles['admin'] && !is_null($request->owner)) {
                $car->title = $request->title;

                if ($request->group !== $car->group_id) {
                    if ($request->group !== 'nothing') {
                        $group = Group::findOrFail($request->group);
                        if (!is_null($group)) {
                            $car->group_id = $request->group;
                            $car->user_id = $group->_id;
                        }
                    }
                }

                if ($request->group == 'nothing') {
                    $car->group_id = null;
                    if ($request->owner == 'nothing') {
                        $car->user_id = null;
                    } elseif (!is_null(User::findOrFail($request->owner))) {
                        $car->user_id = $request->owner;
                    }
                }

                $car->save();
            } elseif ($this->user->role == $this->roles['user'] && $car->user_id == $this->user->_id) {
                $car->title = $request->title;
                if ($request->group == 'nothing') {
                    $car->group_id = null;
                } elseif (Group::where('user_id', $this->user->_id)->findOrFail($request->group)) {
                    $car->group_id = $request->group;
                }
                $car->save();
            }

        }
        return redirect()->back();
    }

    public function deleteCar (Car $car, Request $request) {
        if ($this->user->role == $this->roles['admin']) {
            $car->delete();
        } elseif ($this->user->role == $this->roles['user'] && $car->user_id == $this->user->_id) {
            $car->delete();
        }
        return redirect()->back();
    }

    //если выбрано несколько
    public function editCars (Request $request) {
        if (!is_null($request->group) && !is_null($request->cars)) {

            $checked = [];

            foreach ($request->cars as $car) {
                $checked[] = $car;
            }

            if (!empty($checked)) {
                if ($this->user->role == $this->roles['admin']) {
                    $cars = Car::whereIn('_id', $checked)->get();
                } elseif ($this->user->role == $this->roles['user']) {
                    $cars = Car::where('user_id', $this->user->_id)->whereIn('_id', $checked)->get();
                }

                if ($this->user->role == $this->roles['admin']) {
                    $group = Group::findOrFail($request->group);
                } elseif ($this->user->role == $this->roles['user']) {
                    $group = Group::where('user_id', $this->user->_id)->findOrFail($request->group);
                }

                if ($this->user->role == $this->roles['admin'] && !is_null($request->owner)) {
                    $user = User::findOrFail($request->owner);
                }
                foreach ($cars as $car) {
                    if ($request->group !== 'unchanged' && (!is_null($group) || $request->group == 'nothing')) {
                        $car->group_id = $request->group == 'nothing' ? null : $group->_id;
                    }
                        if ($this->user->role == $this->roles['admin'] && $request->owner !== 'unchanged' && (!is_null($user) || $request->owner == 'nothing') && $car->group_id == null) {
                            $car->user_id = $request->owner == 'nothing' ? null : $user->_id;
                        }
                    $car->save();
                }
            }
        }
        return redirect()->back();
    }

    public function deleteCars (Request $request) {
        if (!is_null($request->cars)) {

            $checked = [];

            foreach ($request->cars as $car) {
                $checked[] = $car;
            }

            if(!empty($checked)) {
                if ($this->user->role == $this->roles['admin']) {
                    $cars = Car::whereIn('_id', $checked)->get();
                } elseif ($this->user->role == $this->roles['user']) {
                    $cars = Car::where('user_id', $this->user->_id)->whereIn('_id', $checked)->get();
                }
                foreach ($cars as $car) {
                    $car->delete();
                }
            }
        }
        return redirect()->back();
    }




    public function сarsSearch (Request $request) {
        if ($request->ajax() && !is_null($request->search)) {
            $result = Car::select('title')->where('title', 'LIKE', '%' . $request->search . '%')->limit(7)->pluck('title');
            $count = $result->count();
            if ($count < 7) {
                $groups = Group::select('title')->where('title', 'LIKE', '%' . $request->search . '%')->limit(7 - $count)->pluck('title');
                $result->merge($groups);
            }
            return response()->json($result);
        }
    }
}
