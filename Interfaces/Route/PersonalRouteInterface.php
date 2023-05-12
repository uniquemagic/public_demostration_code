<?php

namespace App\Interfaces\Route;

interface PersonalRouteInterface
{
    public const PERSONAL_COURSES_ROUTE     = 'personal.courses';
    public const PERSONAL_LITERATURES_ROUTE = 'personal.literatures';
    public const PERSONAL_SCHEDULE_ROUTE    = 'personal.schedule';
    public const PERSONAL_SETTINGS_ROUTE    = 'personal.settings';
    public const PERSONAL_PAYMENTS_ROUTE    = 'personal.payments';

    public const PERSONAL_ROUTES = [
        self::PERSONAL_COURSES_ROUTE,
        self::PERSONAL_LITERATURES_ROUTE,
        self::PERSONAL_SCHEDULE_ROUTE,
        self::PERSONAL_SETTINGS_ROUTE,
        self::PERSONAL_PAYMENTS_ROUTE
    ];

    public const PERSONAL_COURSES_NAME     = 'Курсы';
    public const PERSONAL_LITERATURES_NAME = 'Литература';
    public const PERSONAL_SCHEDULE_NAME    = 'Расписание';
    public const PERSONAL_SETTINGS_NAME    = 'Настройки';
    public const PERSONAL_PAYMENTS_NAME    = 'История платежей';

    public const PERSONAL_ROUTES_NAMES = [
        self::PERSONAL_COURSES_ROUTE     => self::PERSONAL_COURSES_NAME,
        self::PERSONAL_LITERATURES_ROUTE => self::PERSONAL_LITERATURES_NAME,
        self::PERSONAL_SCHEDULE_ROUTE    => self::PERSONAL_SCHEDULE_NAME,
        self::PERSONAL_SETTINGS_ROUTE    => self::PERSONAL_SETTINGS_NAME,
        self::PERSONAL_PAYMENTS_ROUTE    => self::PERSONAL_PAYMENTS_NAME
    ];
}