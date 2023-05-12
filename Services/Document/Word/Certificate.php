<?php

namespace App\Services\Document\Word;

use Illuminate\Http\Request;
use \PhpOffice\PhpWord\TemplateProcessor as TemplateProcessor;
use App\Models\User;
use DateTimeHelper;
use App\Traits\CourseTrait;
use App\Services\Document\AbstractWordDocument;

class Certificate extends AbstractWordDocument
{
    use CourseTrait;

    private const TEMPLATE_FILE    = 'app/public/documents/templates/certificate.docx';
    private const RESULT_FOLDER    = 'app/public/documents/certificates/';
    private const USER_COLUMN_PATH = 'certificate_path';
    private const FAKE_DATA        = 'XXXX';

    private const DOCUMENT_PREFIX  = 'Справка_для_';
 
    private const PARAMS = [
        'contract_signed_at',
        'contract_finished_at',
        'parent_fullname',
        'student_fullname',
        'student_birthdate',
        'course_name',
        'type_of_study',
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

        $birthdate     = isset($this->user->student) ? $this->user->student->birthdate     : $userData['birthdate'];
        
        $data = [
            DateTimeHelper::convertDateToSpellingFormat($userData['contract_signed_at'] ?? date('d.m.Y')),
            DateTimeHelper::convertDateToSpellingFormat($userData['contract_finished_at'] ?? date('d.m.Y')),
            $userData['fullname'] ?? self::FAKE_DATA,
            isset($this->user->student) ? $this->user->student->fullname : ($userData['fullname'] ?? self::FAKE_DATA),
            DateTimeHelper::convertDateToSpellingFormat(date('d.m.Y', strtotime($birthdate))),
            $this->_getCourseName(),
            $this->_getTypeOfStudy(),
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

    public function getSavedFile() {
        return response()->download(storage_path($this->resultFile));
    }
}