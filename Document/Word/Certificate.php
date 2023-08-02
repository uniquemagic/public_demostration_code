<?php

namespace App\Services\Document\Word;

use Illuminate\Http\Request;
use App\Helpers\DateTimeHelper;
use App\Models\Course;
use App\Services\Document\AbstractWordDocument;
use \PhpOffice\PhpWord\TemplateProcessor as TemplateProcessor;

class Certificate extends AbstractWordDocument
{
    private const TEMPLATE_FILE    = 'app/public/documents/templates/certificate.docx';
    private const RESULT_FOLDER    = 'app/public/documents/certificates/';
    private const USER_COLUMN_PATH = 'certificate_path';

    private const DOCUMENT_PREFIX  = 'Certificate_for_';
 
    private const PARAMS = [
        'contract_signed_at',
        'contract_finished_at',
        'parent_fullname',
        'student_fullname',
        'student_birthdate',
        'course_name',
        'type_of_study',
    ];

    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    protected function generateTemplate() 
    {
        return new TemplateProcessor(storage_path(self::TEMPLATE_FILE));
    }

    protected function prepareDataToSave(): array 
    {
        $birthdate = isset($this->user->student) ? $this->user->student->birthdate : $this->user->birthdate;
        
        $data = [
            /* 'contract_signed_at'   */ DateTimeHelper::pronounce( $this->user->contract_signed_at ),
            /* 'contract_finished_at' */ DateTimeHelper::pronounce( $this->user->contract_finished_at ),
            /* 'parent_fullname'      */ $this->user->fullname ?? Course::FAKE_DATA_X,
            /* 'student_fullname'     */ $this->user->getStudentFullname(),
            /* 'student_birthdate'    */ DateTimeHelper::pronounce( date( 'd.m.Y', strtotime( $birthdate ) ) ),
            /* 'course_name'          */ $this->user->getCourseForDocument()->name,
            /* 'type_of_study'        */ $this->user->getTypeOfStudy(),
        ];

        return array_combine(self::PARAMS, $data);
    }

    protected function putTableDataToFile()
    {
        //
    }

    protected function generateResultFileName(string $extension = 'docx'): string
    {
        return self::RESULT_FOLDER . self::DOCUMENT_PREFIX . str_replace(' ', '_', $this->user->fullname) . '.' . $extension;
    }

    protected function attachFileToUser() 
    {
        $this->user->update([
            self::USER_COLUMN_PATH => $this->resultFile
        ]);
    }
}