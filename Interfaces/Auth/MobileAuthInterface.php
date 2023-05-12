<?php

namespace App\Interfaces\Auth;

use Illuminate\Http\Request;

interface MobileAuthInterface
{
    public const KEY = "";

    public const AUTHORIZATION = 'Authorization';

    public const ERROR   = 'error';
    public const SUCCESS = 'success';
    public const TOKEN   = 'token';

    public const INVALID_DATA    = 'invalid data';
    public const INVALID_TOKEN   = 'invalid token';
    public const INVALID_REQUEST = 'invalid request';

    public const IS_NOT_AUTHORIZED  = 'is not authorized';
    public const ALREADY_AUTHORIZED = 'already authorized';
    public const AUTHORIZED         = 'authorized';
    public const LOGGED_OUT         = 'logged out';
    public const REGISTERED         = 'registered';
    public const LOGGING_OUT        = 'logging out';
    public const IMPOSSIBLE         = 'impossible';

    public function token(Request $request);
    public function login(Request $request);
    public function check(Request $request);
    public function logout(Request $request);
    public function register(Request $request);
    public function getBasicAuthStr($str);
    public function json_response($response, $code = 200);
}