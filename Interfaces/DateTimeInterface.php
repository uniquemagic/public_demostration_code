<?php

namespace App\Interfaces;

interface DateTimeInterface
{
    public const MONTHES = [
        'Января',
        'Февраля',
        'Марта',
        'Апреля',
        'Мая',
        'Июня',
        'Июля',
        'Августа',
        'Сентября',
        'Октября',
        'Ноября',
        'Декабря'
    ];

    public const WEEKDAYS = [
        0 => 'ВС',
        1 => 'ПН',
        2 => 'ВТ',
        3 => 'СР',
        4 => 'ЧТ',
        5 => 'ПТ',
        6 => 'СБ',
        7 => 'ВС'
    ];
}