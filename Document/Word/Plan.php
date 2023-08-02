<?php

namespace App\Services\Document\Word;

use App\Services\Document\AbstractWordDocument;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\Element\Table;
use App\Models\Course;
use App\Constants\Education;
use App\Models\Teacher;
use \PhpOffice\PhpWord\TemplateProcessor as TemplateProcessor;

class Plan extends AbstractWordDocument
{
    private const TEMPLATE_FILE    = 'app/public/documents/templates/plan.docx';
    private const RESULT_FOLDER    = 'app/public/documents/plans/';
    private const USER_COLUMN_PATH = '';

    private const DOCUMENT_PREFIX  = 'Plan_for_';
 
    private const PARAMS = [
        'plan_name',
        'plan_author',
        'plan_created_at',
    ];

    private $course;

    public function __construct(Request $request) 
    {
        $this->course = Course::findOrFail($request->course_id);

        parent::__construct($request);
    }

    protected function generateTemplate() 
    {
        return new TemplateProcessor(storage_path(self::TEMPLATE_FILE));
    }

    protected function prepareDataToSave(): array
    {
        $data = [
            /* 'plan_name' */       $this->course->name,
            /* 'plan_author' */     Teacher::find(4)->fullname,
            /* 'plan_created_at' */ date('d.m.Y'),
        ];

        return array_merge(array_combine(self::PARAMS, $data));
    }

    protected function putTableDataToFile()
    {
        $table = new Table(['borderSize' => 12, 'borderColor' => 'black', 'width' => 100]);

        $this->seedTableHeader($table);

        foreach($this->course->lessons as $lesson) {
           $this->seedTableRow($table, $lesson);
        }
        $this->wordDocument->setComplexBlock('plan_table', $table);
    }

    protected function seedTableHeader(&$table)
    {
        $table->addRow();
        $table->addCell()->addText('№',            self::F_STYLE_BOLD, self::P_STYLE);
        $table->addCell()->addText('Урок',         self::F_STYLE_BOLD, self::P_STYLE);
        $table->addCell()->addText('Длительность', self::F_STYLE_BOLD, self::P_STYLE);
    }

    protected function seedTableRow(&$table, $lesson)
    {
        $lessonDurations = $lesson->getDurationInMinutes(Education::TYPE_OF_STUDY_OFFLINE) . '/' . $lesson->getDurationInMinutes(Education::TYPE_OF_STUDY_ONLINE) . ' мин';
        $table->addRow();
        $table->addCell()->addText($lesson->sequence_number, self::F_STYLE_NORMAL, self::P_STYLE);
        $table->addCell()->addText($lesson->name,            self::F_STYLE_NORMAL, self::P_STYLE);
        $table->addCell()->addText($lessonDurations,         self::F_STYLE_NORMAL, self::P_STYLE);
    }

    protected function generateResultFileName(string $extension = self::DOCX): string
    {
        return self::RESULT_FOLDER . self::DOCUMENT_PREFIX . str_replace('-', '_', $this->course->slug) . '.' . $extension;
    }

    protected function attachFileToUser() 
    {
        //
    }
}