<?php

namespace App\Interfaces\Route;

interface ExternalRouteInterface
{
    public const EXTERNAL_WELCOME_ROUTE             = 'pages.welcome';
    public const EXTERNAL_COURSE_ROUTE              = 'pages.course';
    public const EXTERNAL_LOGIN_ROUTE               = 'login';
    public const EXTERNAL_REGISTER_ROUTE            = 'register';
    public const EXTERNAL_SUCCESS_ROUTE             = 'payment.success';
    public const EXTERNAL_FAIL_ROUTE                = 'payment.fail';
    public const EXTERNAL_AUTH_MESSENGER_ROUTE      = 'auth.messenger';
    public const EXTERNAL_AUTH_MESSENGER_CODE_ROUTE = 'auth.messenger.code';
    public const EXTERNAL_PASSWORD_REQUEST_ROUTE    = 'password.request';
    public const EXTERNAL_PASSWORD_RESET_ROUTE      = 'password.reset';

    public const EXTERNAL_ROUTES = [
        self::EXTERNAL_WELCOME_ROUTE,
        self::EXTERNAL_COURSE_ROUTE,
        self::EXTERNAL_LOGIN_ROUTE,
        self::EXTERNAL_REGISTER_ROUTE,
        self::EXTERNAL_SUCCESS_ROUTE,
        self::EXTERNAL_FAIL_ROUTE,
        self::EXTERNAL_AUTH_MESSENGER_ROUTE,
        self::EXTERNAL_AUTH_MESSENGER_CODE_ROUTE,
        self::EXTERNAL_PASSWORD_REQUEST_ROUTE,
        self::EXTERNAL_PASSWORD_RESET_ROUTE
    ];
}