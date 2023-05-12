<?php

namespace App\Interfaces;

interface NumberInterface
{
    public const MIN_VALUE = 0;
    public const MAX_VALUE = 9999;

    public const SPACE  = ' ';
    public const EMPTY  = '';

    public const ZERO  = 'ноль';
    public const ONE   = 'один';
    public const TWO   = 'два';
    public const THREE = 'три';
    public const FOUR  = 'четыре';
    public const FIVE  = 'пять';
    public const SIX   = 'шесть';
    public const SEVEN = 'семь';
    public const EIGHT = 'восемь';
    public const NINE  = 'девять';
    public const TEN   = 'десять';

    public const ONE_A = 'одна';
    public const TWO_E = 'две';

    public const ELEVEN    = 'одиннадцать';
    public const TWELVE    = 'двенадцать';
    public const THIRTEEN  = 'тринадцать';
    public const FOURTEEN  = 'четырнадцать';
    public const FIFTEEN   = 'пятнадцать';
    public const SIXTEEN   = 'шестнадцать';
    public const SEVENTEEN = 'семнадцать';
    public const EIGHTEEN  = 'восемнадцать';
    public const NINETEEN  = 'девятнадцать';

    public const TWENTY  = 'двадцать';
    public const THIRTY  = 'тридцать';
    public const FORTY   = 'сорок';
    public const FIFTY   = 'пятьдесят';
    public const SIXTY   = 'шестьдесят';
    public const SEVENTY = 'семьдесят';
    public const EIGHTY  = 'восемьдесят';
    public const NINETY  = 'девяносто';

    public const ONE_HUNDRED  = 'сто';
    public const TWO_HUDRED   = 'двести';
    public const THREE_HUDRED = 'триста';
    public const FOUR_HUDRED  = 'четыреста';
    public const FIVE_HUDRED  = 'пятьсот';
    public const SIX_HUNDRED  = 'шестьсот';
    public const SEVEN_HUDRED = 'семьсот';
    public const EIGHT_HUDRED = 'восемьсот';
    public const NINE_HUNDRED = 'девятьсот';
    
    // 1-9
    public const UNITS = [
        self::ZERO,
        self::ONE,
        self::TWO,
        self::THREE,
        self::FOUR,
        self::FIVE,
        self::SIX,
        self::SEVEN,
        self::EIGHT,
        self::NINE,
    ];

    public const UNITS_AE = [
        self::ZERO,
        self::ONE_A,
        self::TWO_E
    ];

    /**
     * 11 - 19
     */
    public const BEETWIN_11_19 = [
        self::EMPTY,
        self::ELEVEN,
        self::TWELVE,
        self::THIRTEEN,
        self::FOURTEEN,
        self::FIFTEEN,
        self::SIXTEEN,
        self::SEVENTEEN,
        self::EIGHTEEN,
        self::NINETEEN
    ];

    /**
     * 10, 20, ...
     */
    public const TENS = [
        self::EMPTY,
        self::TEN,
        self::TWENTY,
        self::THIRTY,
        self::FORTY,
        self::FIFTY,
        self::SIXTY,
        self::SEVENTY,
        self::EIGHTY,
        self::NINETY,
    ];

    /**
     * 100, 200, ...
     */
    public const HUNDREDS = [
        self::EMPTY,
        self::ONE_HUNDRED,
        self::TWO_HUDRED,
        self::THREE_HUDRED,
        self::FOUR_HUDRED,
        self::FIVE_HUDRED,
        self::SIX_HUNDRED,
        self::SEVEN_HUDRED,
        self::EIGHT_HUDRED,
        self::NINE_HUNDRED
    ];

    public const SUFFIX_A_THOUSAND = 'тысяча';
    public const SUFFIX_I_THOUSAND = 'тысячи';
    public const SUFFIX_0_THOUSAND = 'тысяч';

    /**
     * Склонения тысяч
     */
    public const UNITS_THOUSANDS = [
        self::EMPTY => self::EMPTY,
        self::ONE_A => self::SUFFIX_A_THOUSAND, // однА тысяча
        self::TWO_E => self::SUFFIX_I_THOUSAND, // двЕ тысячи
        self::THREE => self::SUFFIX_I_THOUSAND,
        self::FOUR  => self::SUFFIX_I_THOUSAND,
        self::FIVE  => self::SUFFIX_0_THOUSAND,
        self::SIX   => self::SUFFIX_0_THOUSAND,
        self::SEVEN => self::SUFFIX_0_THOUSAND,
        self::EIGHT => self::SUFFIX_0_THOUSAND,
        self::NINE  => self::SUFFIX_0_THOUSAND,
    ];

    public const SUFFIX_YA_RUBLES = 'рубля';
    public const SUFFIX_EI_RUBLES = 'рублей';
    public const SUFFIX_0_RUBLES  = 'рубль';

    /**
     * Склонения рублей
     */
    public const UNITS_RUBLES = [
        self::EMPTY => self::EMPTY,
        self::ZERO  => self::SUFFIX_EI_RUBLES,
        self::ONE   => self::SUFFIX_0_RUBLES,
        self::TWO   => self::SUFFIX_YA_RUBLES,
        self::THREE => self::SUFFIX_YA_RUBLES,
        self::FOUR  => self::SUFFIX_YA_RUBLES,
        self::FIVE  => self::SUFFIX_EI_RUBLES,
        self::SIX   => self::SUFFIX_EI_RUBLES,
        self::SEVEN => self::SUFFIX_EI_RUBLES,
        self::EIGHT => self::SUFFIX_EI_RUBLES,
        self::NINE  => self::SUFFIX_EI_RUBLES,
    ];
}