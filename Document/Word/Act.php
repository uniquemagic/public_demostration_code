<?php

namespace App\Services\Document\Word;

use Illuminate\Http\Request;
use App\Helpers\DateTimeHelper;
use App\Models\Course;
use Money;
use App\Services\Document\AbstractWordDocument;
use \PhpOffice\PhpWord\TemplateProcessor as TemplateProcessor;

class Act extends AbstractWordDocument
{
    private const TEMPLATE_FILE    = 'app/public/documents/templates/act.docx';
    private const RESULT_FOLDER    = 'app/public/documents/acts/';
    private const USER_COLUMN_PATH = 'act_path';

    private const DOCUMENT_PREFIX  = 'Act_for_';
 
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
        $data = [
            /* 'act_number' */            Course::FAKE_DATA_X,
            /* 'contract_finished_at' */  DateTimeHelper::pronounce( $this->user->contract_finished_at ),
            /* 'parent_fullname' */       $this->user->fullname         ?? Course::FAKE_DATA_X,
            /* 'passport_address' */      $this->user->passport_address ?? Course::FAKE_DATA_X,
            /* 'parent_phone' */          $this->user->phone            ?? Course::FAKE_DATA_X,
            /* 'course_name' */           $this->user->getCourseForDocument()->name,
            /* 'type_of_study' */         $this->user->getTypeOfStudy(),
            /* 'count' */                 Course::BOUGHT_COURSES_COUNT,
            /* 'course_price' */          $this->user->getBenefit()->setCurrency(Money::RUB)->format(), // Для акта формат суммы в рублях (р)
            /* 'course_total' */          $this->user->getBenefit()->setCurrency(Money::RUB)->multiply(Course::BOUGHT_COURSES_COUNT)->format(),
            /* 'course_total_spelling' */ $this->user->getBenefit()->pronounce()
        ];

        return array_combine(self::PARAMS, $data);
    }

    protected function putTableDataToFile()
    {
        //
    }

    protected function generateResultFileName(string $extension = self::DOCX): string
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