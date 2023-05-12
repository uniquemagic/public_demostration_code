<?php

namespace App\Services\Parser;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class Faq extends AbstractParser
{
    private const URL     = '';
    private const PERIOD  = 86400;
    private const TABLE   = 'faqs';
    private const MODEL   = 'App\\Models\\Faq';
    private const COLUMNS = [
        'author',
        'header',
        'description'
    ];
    private const QUERY_PARAM = [
        'project_id' => 1
    ];
    
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
        $response = Http::get(self::URL, self::QUERY_PARAM);
        if ($response->status() !== 200) {
            return [];
        }
        return $response->json()[self::TABLE];
    }

    protected function putDataToTable() 
    {
        if ($this->_data) {
            foreach ($this->_data as $row) {
                $row = [
                    $row['author'],
                    $row['header'],
                    $row['description']
                ];
                self::MODEL::updateOrCreate(array_combine(self::COLUMNS, $row));
            }
        }
    }
}