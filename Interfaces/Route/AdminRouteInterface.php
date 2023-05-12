<?php

namespace App\Interfaces\Route;

interface AdminRouteInterface
{
    public const ADMIN_USERS_ROUTE          = 'users.index';
    public const ADMIN_SCHEDULE_ROUTE       = 'schedule.index';
    public const ADMIN_COMMUNICATIONS_ROUTE = 'communications.index';
    public const ADMIN_SEO_ROUTE            = 'seo.index';

    public const ADMIN_ROUTES = [
        self::ADMIN_USERS_ROUTE,
        self::ADMIN_SCHEDULE_ROUTE,
        self::ADMIN_COMMUNICATIONS_ROUTE,
        self::ADMIN_SEO_ROUTE
    ];

    public const ADMIN_USERS_DESCRIPTION          = 'Если появился новый ученик, заполняем данные через кнопку "Новый". Через "Редактировать" Генерируем договор -> печатаем -> подписываем -> загружаем там же скан';
    public const ADMIN_SCHEDULE_DESCRIPTION       = 'Расписание занятий по всем ученикам на текущий месяц';
    public const ADMIN_COMMUNICATIONS_DESCRIPTION = 'Отправка уведомлений по whatsapp, почте как в автоматическом режиме, так и вручную';
    public const ADMIN_SEO_DESCRIPTION            = 'SEO для страниц сайта';

    public const ADMIN_ROUTES_DESCRIPTIONS = [
        self::ADMIN_USERS_ROUTE          => self::ADMIN_USERS_DESCRIPTION,
        self::ADMIN_SCHEDULE_ROUTE       => self::ADMIN_SCHEDULE_DESCRIPTION,
        self::ADMIN_COMMUNICATIONS_ROUTE => self::ADMIN_COMMUNICATIONS_DESCRIPTION,
        self::ADMIN_SEO_ROUTE            => self::ADMIN_SEO_DESCRIPTION,
    ];
}