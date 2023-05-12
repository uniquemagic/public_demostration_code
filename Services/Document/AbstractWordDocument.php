<?php 

namespace App\Services\Document;

abstract class AbstractWordDocument
{
    /**
     * Используем трейты по необходимости
     */
    
    /**
     * Ссылка на файл-шаблон word
     */
    private const TEMPLATE_FILE    = '';
    /**
     * Путь сохранения по типу документа (договор, справка и т.д.)
     */
    private const RESULT_FOLDER    = '';
    /**
     * Поле в БД для сохранения пути
     */
    private const USER_COLUMN_PATH = '';
    /**
     * Параметры для замены в шаблоне
     */
    private const PARAMS           = [];
    /**
     * Эти данные подставятся в шаблон вместо неустановленного значения
     */
    private const FAKE_DATA        = 'XXXX';
    /**
     * Префикс для имени документа
     */
    private const DOCUMENT_PREFIX  = '';
    /**
     * Объект генератора
     */
    private $word;
    /**
     * Для кого генерируем
     */
    private $user;
    /**
     * Данные для записи
     */
    private $dataToSave;
    private $resultFile;

    abstract protected function prepareDataToSave(): array;
    abstract protected function putDataToFile();
    abstract protected function generateResultFileName(string $extension = 'docx'): string;
    abstract protected function saveFile();
    abstract protected function attachFileToUser();
    abstract public function getSavedFile();
}