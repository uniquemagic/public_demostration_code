<?php

namespace App\Entities\Finance;

use Exception;
/**
 * Создает объект деньги без копеек и с валютой рубли
 * Предоставляет методы по суммированию и др.
 * Тип amount для денег - integer
 */
class Money
{
    public const FORMAT_DIGIT = 'digit'; // с пробелами в разрядах

    public const EXCEPTION_CURRENCIES_DO_NOT_MATCH   = 'currencies do not match';
    public const EXCEPTION_CANNON_SAY_NEGATIVE_MONEY = 'cannot say negative money';

    public const RUB           = 'р';
    public const RUB_PER_MONTH = 'р/мес';

    private $_amount;
    private $_currency;

    public function __construct($amount, $currency)
    {
        $this->_amount   = $amount;
        $this->_currency = $currency;
    }

    public static function create($amount = 0, $currency = self::RUB) 
    {
        return new self((int) $amount, $currency);
    }

    public function getAmount(): int
    {
        return $this->_amount;
    }

    public function getCurrency(): string
    {
        return $this->_currency;
    }

    public function setAmount($amount)
    {
        $this->_amount = $amount;
        return $this;
    }

    public function setCurrency($currency)
    {
        $this->_currency = $currency;
        return $this;
    }

    // Привести к желаемому формату
    public function format($format = null): string
    {
        switch(true) {
            case $format == self::FORMAT_DIGIT:
                return number_format($this->getAmount(), 0, ',', ' ');
            default:
                return $this->getAmount() . ' ' . $this->getCurrency();
        }
    }

    // Увеличить сумму на $summandMoney. Для вычитания предварительно применить $summandMoney->negate()
    public function add(Money $summandMoney): Money
    {
        if ( $this->getCurrency() !== $summandMoney->getCurrency() ) {
            throw new Exception(self::EXCEPTION_CURRENCIES_DO_NOT_MATCH);
        }
        $this->_amount = $this->getAmount() + $summandMoney->getAmount();
        return $this;
    }

    public function multiply($number): Money
    {
        $this->_amount = $number * $this->getAmount();
        return $this;
    }

    public function equals(Money $equaledMoney): bool
    {
        if ( $this->getCurrency() !== $equaledMoney->getCurrency() ) {
            throw new Exception(self::EXCEPTION_CURRENCIES_DO_NOT_MATCH);
        }

        return $this->getAmount() === $equaledMoney->getAmount();
    }

    // Поменять сумму на противоположную по знаку
    public function negate(): Money
    {
        $this->_amount = (-1) * $this->getAmount();
        return $this;
    }

    // Является ли сумма отрицательной по величине
    public function isNegative(): bool
    {
        return $this->getAmount() < 0;
    }

    public function isZero(): bool
    {
        return $this->getAmount() == 0;
    }

    /**
     * Функция, преобразующая целое значение суммы от MIN_VALUE до MAX_VALUE в прописной вид
     */
    public function pronounce(): string 
    {
        if ( $this->isNegative() ) {
            throw new Exception(Money::EXCEPTION_CANNON_SAY_NEGATIVE_MONEY);
        }

        $amount = $this->getAmount();

        if ( $amount < Unit::MIN_VALUE || $amount > Unit::MAX_VALUE ) {
            return Unit::EMPTY;
        }

        if ( $amount < 10 ) {
            $unit = Unit::UNITS[$amount];
            return $unit . Unit::SPACE . Unit::UNITS_RUBLES[$unit]; // 1 рубль, 2 рубля,...
        }

        if ( $amount >= 10 && $amount < 20 ) {
            $suffix = Unit::UNITS_RUBLES[Unit::ZERO];
            return 
                (
                    $amount == 10
                    ? Unit::TENS[$amount - 9]
                    : Unit::BEETWIN_11_19[$amount - 10]
                ) . Unit::SPACE . $suffix;
        }

        if ( $amount >= 20 && $amount < 100 ) {
            $ten    = (int) ($amount / 10); // находим десяток числа
            $unit   = Unit::UNITS[$amount - $ten * 10];
            $suffix = Unit::UNITS_RUBLES[$unit];
            return Unit::TENS[$ten] . Unit::SPACE . ($unit == Unit::ZERO ? Unit::EMPTY : $unit) . Unit::SPACE . $suffix;
        }

        if ( $amount >= 100 && $amount < 1000 ) {
            $hundred = (int) ($amount / 100);
            $ten     = (int) (($amount - $hundred * 100) / 10);
            $unit    = Unit::UNITS[$amount - $hundred * 100 - $ten * 10];
            $suffix  = Unit::UNITS_RUBLES[$unit];
            return 
                  Unit::HUNDREDS[$hundred] 
                . Unit::SPACE 
                . Unit::TENS[$ten] 
                . Unit::SPACE 
                . ( $unit == Unit::ZERO ? Unit::EMPTY : $unit )
                . Unit::SPACE 
                . $suffix;
        }

        if ( $amount >= 1000 && $amount < 9999 ) { // 1368
            $thousand = (int) ($amount / 1000); // 1
            $hundred  = (int) (($amount - $thousand * 1000) / 100); // 3
            $ten      = (int) (($amount - $thousand * 1000 - $hundred * 100) / 10); // 6
            $unit     = Unit::UNITS[$amount - $thousand * 1000 - $hundred * 100 - $ten * 10]; // 8
            $suffix   = Unit::UNITS_RUBLES[$unit];
            $thousandSpelling = ($thousand == 1 || $thousand == 2)
                ? Unit::UNITS_AE[$thousand]
                : Unit::UNITS   [$thousand];

            return $thousandSpelling
                . Unit::SPACE
                . Unit::UNITS_THOUSANDS[$thousandSpelling]
                . Unit::SPACE 
                . Unit::HUNDREDS[$hundred]
                . Unit::SPACE 
                . Unit::TENS[$ten]
                . Unit::SPACE 
                . ( $unit == Unit::ZERO ? Unit::EMPTY : $unit )
                . Unit::SPACE 
                . $suffix;
        }
        return $amount;
    }
}