<?php

namespace App\Services\Document\Word;

use Illuminate\Http\Request;
use \PhpOffice\PhpWord\TemplateProcessor as TemplateProcessor;
use App\Models\User;
use DateTimeHelper;
use App\Traits\CourseTrait;
use Money;
use App\Services\Document\AbstractWordDocument;

class Contract extends AbstractWordDocument
{
    use CourseTrait;
    
    private const TEMPLATE_FILE    = 'app/public/documents/templates/contract.docx';
    private const RESULT_FOLDER    = 'app/public/documents/contracts/';
    private const USER_COLUMN_PATH = 'contract_path';
    private const FAKE_DATA        = 'XXXX';

    private const DOCUMENT_PREFIX  = 'Договор_с_';
 
    private const PARAMS = [
        'city',
        'contract_signed_at',
        'parent_fullname',
        'student_fullname',
        'student_birthdate',
        'lesson_duration',
        'frequency',
        'course_name',
        'course_duration',
        'type_of_study',
        'course_price',
        'passport_number',
        'passport_address',
        'parent_phone',
        'super_admin_email',
    ];

    private const LESSON_DURATION  = 90; // длительность в минутах 1 урока
    private const COURSE_DURATION  = 24; // 24 занятия за курс (6 месяцев)
    private const LESSON_FREQUENCY = 4;  // 4 раза в месяц, один раз в неделю

    private const PARAM_TABLE_MONTH    = 'table_month';
    private const PARAM_TABLE_PAY_DATA = 'table_pay_data';

    private $word;
    private $user;
    private $dataToSave;
    private $resultFile;

    public function __construct(Request $request) {
       $this->word       = new TemplateProcessor(storage_path(self::TEMPLATE_FILE));
       $this->user       = User::find($request->user_id);
       $this->dataToSave = $this->prepareDataToSave();
       $this->putDataToFile();
       $this->resultFile = self::RESULT_FOLDER . $this->generateResultFileName();
       // Также добавляем ссылку на подписанный pdf, который позже необходимо догрузить вручную
       $this->user->update([
            'contract_signed_path' => self::RESULT_FOLDER . 'signed/' . $this->generateResultFileName('pdf')
       ]);
       $this->saveFile();
       $this->attachFileToUser();
    }

    protected function prepareDataToSave(): array 
    {
        $userData = $this->user;

        $contractSignedAt = $userData['contract_signed_at'] ?? date('d.m.Y');

        $data = [
            /* 'city' */               $userData['city'] ?? self::FAKE_DATA,
            /* 'contract_signed_at' */ DateTimeHelper::convertDateToSpellingFormat($contractSignedAt),
            /* 'parent_fullname' */    $userData['fullname'] ?? self::FAKE_DATA,
            /* 'student_fullname' */   isset($this->user->student) ? $this->user->student->fullname : ($userData['fullname'] ?? self::FAKE_DATA),
            /* 'student_birthdate' */  date('d.m.Y', strtotime(isset($this->user->student) ? $this->user->student->birthdate : $userData['birthdate'])),
            /* 'lesson_duration' */    self::LESSON_DURATION,
            /* 'frequency' */          self::LESSON_FREQUENCY == 4 ? 'один раз в неделю' : self::FAKE_DATA,
            /* 'course_name' */        $this->_getCourseName(),
            /* 'course_duration' */    self::COURSE_DURATION,
            /* 'type_of_study' */      $this->_getTypeOfStudy(),
            /* 'course_price' */       Money::getMonthIncomeByUser($this->user) . ' р/мес ',
            /* 'passport_number' */    $userData['passport_number'] ?? self::FAKE_DATA,
            /* 'passport_address' */   $userData['passport_address'] ?? self::FAKE_DATA,
            /* 'parent_phone' */       $userData['phone'] ?? self::FAKE_DATA,
            /* 'super_admin_email' */  config('constants.SUPER_ADMIN_EMAIL') ?? self::FAKE_DATA
        ];

        return array_merge(array_combine(self::PARAMS, $data), $this->prepareTableData($contractSignedAt));
    }

    protected function prepareTableData($contractSignedAt)
    {
        $tableData = [];
        for ($monthNumber = 1; $monthNumber <= (int) (self::COURSE_DURATION / self::LESSON_FREQUENCY); $monthNumber++) {
            $monthDate = date('Y-m-d H:i:s', strtotime('+' . (int)($monthNumber - 1) . ' month', strtotime($contractSignedAt)));
            $tableData = array_merge($tableData, [
                self::PARAM_TABLE_MONTH    . '_' . $monthNumber => $monthNumber . ' месяц',
                self::PARAM_TABLE_PAY_DATA . '_' . $monthNumber => DateTimeHelper::convertDateToSpellingFormat($monthDate), 
            ]);
        }
        return $tableData;
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

    public static function uploadFile(Request $request) {
        $request->file('file')
            ->storeAs(
                str_replace('app/', '', self::RESULT_FOLDER . 'signed/'), 
                basename(User::find($request->user_id)->contract_signed_path)
            );
    }
}