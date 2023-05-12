<?php

namespace App\Interfaces\Seo;

use App\Services\Seo\Meta;

interface MetaInterface
{
    public const BASE_CANONICAL = 'https://uniquemagic.ru/';

    public const SPACE        = ' ';
    public const COMMA        = ',';
    public const DELIMETER    = ' | ';
    public const IN_CITY      = 'в Твери';
    public const COMPANY_NAME = 'Unique Magic';
    public const TITLE_SUFFIX = self::SPACE . self::IN_CITY . self::DELIMETER . self::COMPANY_NAME;

    public const TITLE       = 'title';
    public const DESCRIPTION = 'description';
    public const KEYWORDS    = 'keywords';

    public const WELCOME_TITLE       = 'Курсы программирования';
    public const WELCOME_DESCRIPTION = 'Компьютерные курсы для детей и взрослых. Первое занятие бесплатно. Низкие цены и трудоустройство после обучения. Выдача сертификата об окончании. Записаться по тел. 8 (904) 004-13-52';
    public const WELCOME_KEYWORDS    = 'школа, программирование, тверь, верстка, язык, сертификат, пробный урок, онлайн, с нуля, разработка';

    public const LOGIN_TITLE       = 'Вход в личный кабинет';
    public const LOGIN_DESCRIPTION = 'Войти в кабинет и получить доступ к курсам';
    public const LOGIN_KEYWORDS    = 'войти, регистрация, забыли пароль, логин, email';

    public const REGISTER_TITLE       = 'Регистрация на сайте';
    public const REGISTER_DESCRIPTION = 'Зарегистрироваться и начать обучение в школе программирования Unique Magic';
    public const REGISTER_KEYWORDS    = 'войти, регистрация, забыли пароль, логин, email';

    public const MESSENGER_TITLE       = 'Войти через WhatsApp';
    public const MESSENGER_DESCRIPTION = 'Авторизуйтесь через WhatsApp по номеру телефона. Войдите сейчас и получите доступ к курсам на сайте';
    public const MESSENGER_KEYWORDS    = 'войти, код, whatsapp, телефон';

    public const MESSENGER_CODE_TITLE       = 'Ввести код из WhatsApp';
    public const MESSENGER_CODE_DESCRIPTION = 'Введите код и получите доступ к курсам на сайте. Укажите телефон для получения кода';
    public const MESSENGER_CODE_KEYWORDS    = 'ввести, авторизация, код, whatsapp, телефон';

    public const SUCCESS_TITLE       = 'Вы купили курс';
    public const SUCCESS_DESCRIPTION = 'Успешное выполнение оплаты на сайте';
    public const SUCCESS_KEYWORDS    = 'нужные, на почте, данные, прошла успешно, личный кабинет';

    public const FAIL_TITLE       = 'Оплата не прошла';
    public const FAIL_DESCRIPTION = 'Неудачное выполнение оплаты на сайте';
    public const FAIL_KEYWORDS    = 'не смогли, принять, еще раз';

    public const PASSWORD_REQUEST_TITLE       = 'Восстановление пароля';
    public const PASSWORD_REQUEST_DESCRIPTION = 'Сбросить пароль и получить доступ в личный кабинет сайта для просмотра курсов';
    public const PASSWORD_REQUEST_KEYWORDS    = 'восстановление пароля, забыл пароль, ввести email';

    public const PASSWORD_RESET_TITLE       = 'Сброс пароля';
    public const PASSWORD_RESET_DESCRIPTION = 'Задать новый пароль для входа в личный кабинет';
    public const PASSWORD_RESET_KEYWORDS    = 'сбросить пароль, новый пароль';

    public const WELCOME_META = [
        self::TITLE       => self::WELCOME_TITLE,
        self::DESCRIPTION => self::WELCOME_DESCRIPTION,
        self::KEYWORDS    => self::WELCOME_KEYWORDS,
    ];

    public const COURSE_META = [
        self::TITLE       => NULL,
        self::DESCRIPTION => NULL,
        self::KEYWORDS    => NULL,
    ];

    public const LOGIN_META = [
        self::TITLE       => self::LOGIN_TITLE,
        self::DESCRIPTION => self::LOGIN_DESCRIPTION,
        self::KEYWORDS    => self::LOGIN_KEYWORDS,
    ];

    public const REGISTER_META = [
        self::TITLE       => self::REGISTER_TITLE,
        self::DESCRIPTION => self::REGISTER_DESCRIPTION,
        self::KEYWORDS    => self::REGISTER_KEYWORDS,
    ];

    public const MESSENGER_META = [
        self::TITLE       => self::MESSENGER_TITLE,
        self::DESCRIPTION => self::MESSENGER_DESCRIPTION,
        self::KEYWORDS    => self::MESSENGER_KEYWORDS,
    ];

    public const MESSENGER_CODE_META = [
        self::TITLE       => self::MESSENGER_CODE_TITLE,
        self::DESCRIPTION => self::MESSENGER_CODE_DESCRIPTION,
        self::KEYWORDS    => self::MESSENGER_CODE_KEYWORDS,
    ];

    public const SUCCESS_META = [
        self::TITLE       => self::SUCCESS_TITLE,
        self::DESCRIPTION => self::SUCCESS_DESCRIPTION,
        self::KEYWORDS    => self::SUCCESS_KEYWORDS,
    ];

    public const FAIL_META = [
        self::TITLE       => self::FAIL_TITLE,
        self::DESCRIPTION => self::FAIL_DESCRIPTION,
        self::KEYWORDS    => self::FAIL_KEYWORDS,
    ];

    public const PASSWORD_REQUEST_META = [
        self::TITLE       => self::PASSWORD_REQUEST_TITLE,
        self::DESCRIPTION => self::PASSWORD_REQUEST_DESCRIPTION,
        self::KEYWORDS    => self::PASSWORD_REQUEST_KEYWORDS,
    ];

    public const PASSWORD_RESET_META = [
        self::TITLE       => self::PASSWORD_RESET_TITLE,
        self::DESCRIPTION => self::PASSWORD_RESET_DESCRIPTION,
        self::KEYWORDS    => self::PASSWORD_RESET_KEYWORDS,
    ];

    public const ROUTES_META = [
        self::WELCOME_META,
        self::COURSE_META,
        self::LOGIN_META,
        self::REGISTER_META,
        self::SUCCESS_META,
        self::FAIL_META,
        self::MESSENGER_META,
        self::MESSENGER_CODE_META,
        self::PASSWORD_REQUEST_META,
        self::PASSWORD_RESET_META,
    ];

    public function combineRoutesAndMeta(): array;

    public function getCrudeTitle(): ?string;

    public function getCrudeDescription(): ?string;

    public function getCrudeKeywords(): ?string;

    public function getCrudeCanonical(): string;

    public function getMeta(): Meta;
}