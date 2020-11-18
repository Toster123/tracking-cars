<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\API\APIController as APIController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use App\Event;
use App\Events\WebsocketEvent;

use App\User;

class AuthController extends APIController
{
    public function login (Request $request) {
        if ($request->email && $request->password) {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                if (Hash::check($request->password, $user->password)) {
                    if ($user->verified_at) {
                        $token = $user->createToken(config('app'));

                        $user_event = $user->events()->create(['type' => 2, 'title' => $user->name]);
                        WebsocketEvent::dispatch($user_event);

                        return $this->sendResponse(['access_token' => $token->accessToken, 'id' => $token->token->id]);
                    }
                    return $this->sendError('Пользователь не верифицирован', 400);
                }
                return $this->sendError('Неверный пароль', 403);
            }
            return $this->sendError('Пользователь с такой почтой не найден', 404);
        }
        return $this->sendError('Отсутствуют обязательные поля', 400);
    }

    public function logout (Request $request) {
        $this->user->token()->revoke();

        $user_event = $this->user->events()->create(['type' => 3, 'title' => $this->user->name]);
        WebsocketEvent::dispatch($user_event);

        $this->sendResponse(true);
    }
}
