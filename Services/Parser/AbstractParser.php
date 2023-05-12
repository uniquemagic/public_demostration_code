<?php

namespace App\Services\Parser;

abstract class AbstractParser
{
    /**
     * URL cтраницы/API, откуда парсим информацию
     */
    private const URL         = '';
    /**
     * Период (в секундах) обновления таблицы
     */
    private const PERIOD      = '';
    /**
     * Таблица, куда помещаем данные
     */
    private const TABLE       = '';
    /**
     * Имя модели, управляющая таблицей
     */
    private const MODEL       = '';
    /**
     * Колонки таблицы для данных
     */
    private const COLUMNS     = [];
    private const QUERY_PARAM = [];

    private $_data;

    abstract protected function isTableAndModelExist(): bool;
    abstract protected function isTimeForUpdate(): bool;
    abstract protected function getLastUpdatedTime(): int;
    abstract protected function prepareDataToSave(): array;
    abstract protected function putDataToTable();
}