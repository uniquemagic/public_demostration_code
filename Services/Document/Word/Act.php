<?php

namespace App\Services\Document\Word;

use Illuminate\Http\Request;
use \PhpOffice\PhpWord\TemplateProcessor as TemplateProcessor;
use App\Models\User;
use DateTimeHelper;
use App\Traits\CourseTrait;
use Money;
use App\Services\Document\AbstractWordDocument;

class Act extends AbstractWordDocument
{
    use CourseTrait;

    private const TEMPLATE_FILE    = 'app/public/documents/templates/act.docx';
    private const RESULT_FOLDER    = 'app/public/documents/acts/';
    private const USER_COLUMN_PATH = 'act_path';
    private const FAKE_DATA        = 'XXXX';

    private const DOCUMENT_PREFIX  = 'Акт_для_';
 
    private const PARAMS = [
        'act_number',
        'contract_finished_at',
        'parent_fullname',
        'passport_address',
        'parent_phone',
        'course_name',
        'type_of_study',
        'count',
        'course_price',
        'course_total',
        'course_total_spelling', // Сумма прописью
    ];

    private $word;
    private $user;
    private $dataToSave;
    private $resultFile;

    public function __construct(Request $request) 
    {
       $this->word       = new TemplateProcessor(storage_path(self::TEMPLATE_FILE));
       $this->user       = User::find($request->user_id);
       $this->dataToSave = $this->prepareDataToSave();
       $this->putDataToFile();
       $this->resultFile = self::RESULT_FOLDER . $this->generateResultFileName();
       $this->saveFile();
       $this->attachFileToUser();
    }

    protected function prepareDataToSave(): array
     {
        $userData = $this->user;

        $userMonthIncome = Money::getMonthIncomeByUser($this->user);

        $data = [
            self::FAKE_DATA, // @todo переделать на нормальный
            DateTimeHelper::convertDateToSpellingFormat($userData['contract_finished_at'] ?? date('d.m.Y')),
            $userData['fullname'] ?? self::FAKE_DATA,
            $userData['passport_address'] ?? self::FAKE_DATA,
            $userData['phone'] ?? self::FAKE_DATA,
            $this->_getCourseName(),
            $this->_getTypeOfStudy(),
            1,
            $userMonthIncome . ' р ',
            $userMonthIncome . ' р ', // ед. умножить на цену
            Money::convertMoneyToSpelling($userMonthIncome),
        ];

        return array_combine(self::PARAMS, $data);
    }

    protected function putDataToFile() 
    {
        foreach($this->dataToSave as $param => $value) {
            $this->word->setValue($param, $value);
        }
    }

    protected function generateResultFileName(string $extension = 'docx'): string
    {
        return self::DOCUMENT_PREFIX . str_replace(' ', '_', $this->user->fullname) . '.' . $extension;
    }

    protected function saveFile() 
    {
        try {
            $this->word->saveAs(storage_path($this->resultFile));
        } 
        catch (\Exception $e) {
            print($e->getMessage());
        }
    }

    protected function attachFileToUser() 
    {
        $this->user->update([
            self::USER_COLUMN_PATH => $this->resultFile
        ]);
    }

    public function getSavedFile() 
    {
        return response()->download(storage_path($this->resultFile));
    }
}