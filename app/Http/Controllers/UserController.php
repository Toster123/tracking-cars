<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public $roles = [
        'admin' => 1,
        'user' => 2,
    ];

    public $user;

    public static function construct () {
        self::$user = Auth::user();
    }
    public function __construct()
    {
        $this->user = Auth::user();
        $this->middleware(function ($request, $next) {
        $this->user = Auth::user();

        return $next($request);
    });
    }
}
