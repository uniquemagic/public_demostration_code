<?php

namespace App\Services\Parser;

use Illuminate\Support\Facades\Schema;
use PHPHtmlParser\Dom;

class Review extends AbstractParser
{
    private const URL     = '';
    private const PERIOD  = 86400;
    private const TABLE   = 'reviews';
    private const MODEL   = 'App\\Models\\Review';
    private const COLUMNS = [
        'name',
        'date',
        'text'
    ];

    private const DIV_REVIEW_NAME = '<div class="review__name" data-name="feedback-user-name">';
    private const DIV_REVIEW_DATE = '<div class="review__date" data-name="feedback-user-date">';
    private const DIV_REVIEW_TEXT = '<div class="review__text" data-name="feedback-user-review-text">';
    private const DIV_CLOSED      = '</div>';

    private $_data;

    public function __construct() 
    {
        if ($this->isTableAndModelExist() && $this->isTimeForUpdate()) {
            $this->_data = $this->prepareDataToSave();
            $this->putDataToTable();
        }
    }

    protected function isTableAndModelExist(): bool
    {
        return Schema::hasTable(self::TABLE) && class_exists(self::MODEL);
    }

    protected function isTimeForUpdate(): bool
    {
        $lastUpdatedTime = $this->getLastUpdatedTime();
        return self::PERIOD < (strtotime('now') - $lastUpdatedTime);
    }

    protected function getLastUpdatedTime(): int
    {
        $last_record = self::MODEL::first();
        if ($last_record !== null) {
            return strtotime($last_record->updated_at);
        }
        return 0;
    }

    protected function prepareDataToSave(): array
    {
        $dom = new Dom;
        $dom->loadFromUrl(self::URL);
        $data = [];
        foreach ($dom->find('.rating__item') as $element) {
            $html = $element->outerHtml;
            $data[] =[
                $this->_getStringBetween($html, self::DIV_REVIEW_NAME, self::DIV_CLOSED),
                $this->_getStringBetween($html, self::DIV_REVIEW_DATE, self::DIV_CLOSED),
                $this->_getStringBetween($html, self::DIV_REVIEW_TEXT, self::DIV_CLOSED)
            ];
        }
        return $data;
    }

    protected function putDataToTable() 
    {
        if ($this->_data) {
            foreach ($this->_data as $row) {
                self::MODEL::updateOrCreate(array_combine(self::COLUMNS, $row));
            }
        }
    }

    private function _getStringBetween($string, $start, $end): string
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) {
            return '';
        }
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }
}