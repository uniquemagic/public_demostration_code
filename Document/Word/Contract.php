<?php

namespace App\Services\Document\Word;

use Illuminate\Http\Request;
use PhpOffice\PhpWord\Element\Table;
use App\Models\User;
use App\Models\Course;
use App\Helpers\DateTimeHelper;
use App\Services\Document\AbstractWordDocument;
use \PhpOffice\PhpWord\TemplateProcessor as TemplateProcessor;
use Money;

class Contract extends AbstractWordDocument
{   
    private const TEMPLATE_FILE    = 'app/public/documents/templates/contract.docx';
    private const RESULT_FOLDER    = 'app/public/documents/contracts/';
    private const USER_COLUMN_PATH = 'contract_path';

    private const USER_COLUMN_PATH_SIGNED = 'contract_signed_path';

    private const SIGNED_PATH = 'signed';

    private const DOCUMENT_PREFIX  = 'Contract_with_';
 
    private const PARAMS = [
        'city',
        'parent_fullname',
        'contract_signed_at',
        'student_fullname',
        'student_birthdate',
        'lesson_duration',
        'frequency',
        'course_name',
        'course_duration', // длительность курса в штуках
        'type_of_study',
        'course_price',
        'passport_number',
        'passport_address',
        'parent_phone',
        'super_admin_email',
    ];

    private const PARAM_TABLE_MONTH    = 'table_month';
    private const PARAM_TABLE_PAY_DATA = 'table_pay_data';

    public function __construct(Request $request) {
        parent::__construct($request);
    }

    protected function generateTemplate() 
    {
        return new TemplateProcessor(storage_path(self::TEMPLATE_FILE));
    }

    protected function prepareDataToSave(): array
    {
        $user = $this->user;

        $data = [
            /* 'city' */               $user->city     ?? Course::FAKE_DATA_X,
            /* 'parent_fullname' */    $this->user->fullname ?? Course::FAKE_DATA_X,
            /* 'contract_signed_at' */ DateTimeHelper::pronounce($this->contractSignedAt),
            /* 'student_fullname' */   $this->user->getStudentFullname(),
            /* 'student_birthdate' */  date('d.m.Y', strtotime(isset($this->user->student) ? $this->user->student->birthdate : $this->user->birthdate)),
            /* 'lesson_duration' */    $this->user->getCourseForDocument()->getFirstLessonDurationInMinutes($this->user->getTypeOfStudy()),
            /* 'frequency' */          $this->user->getCourseFrequencyForDocument(),
            /* 'course_name' */        $this->user->getCourseForDocument()->name,
            /* 'course_duration' */    $this->user->getCourseForDocument()->getDurationInLessons(),
            /* 'type_of_study' */      $this->user->getTypeOfStudy(),
            /* 'course_price' */       $this->user->getBenefit()->setCurrency($this->user->getCoursePaymentMethod())->format(),
            /* 'passport_number' */    $this->user->passport_number          ?? Course::FAKE_DATA_X,
            /* 'passport_address' */   $this->user->passport_address         ?? Course::FAKE_DATA_X,
            /* 'parent_phone' */       $this->user->phone                    ?? Course::FAKE_DATA_X,
            /* 'super_admin_email' */  config('constants.SUPER_ADMIN_EMAIL') ?? Course::FAKE_DATA_X
        ];

        return array_merge(array_combine(self::PARAMS, $data));
    }

    protected function putTableDataToFile()
    {
        $table = new Table(['borderSize' => 12, 'borderColor' => 'black', 'width' => 100]);

        /**
         * Находим максимальное число месяцев для таблицы приложения договора
         * 
         * Если способ оплаты сразу за весь курс, то в договоре только один месяц со всей оплатой
         * Иначе находим длительность из курса документа
         */
        $maxMonthNumbers = $this->user->individual_payment_method == Money::RUB ? 1 : $this->user->getCourseForDocument()->getDurationInMonths();

        for ( $monthNumber = 1; $monthNumber <= $maxMonthNumbers; $monthNumber++ ) {
            $monthDate = date('Y-m-d H:i:s', strtotime('+' . (int)($monthNumber - 1) . ' month', strtotime($this->contractSignedAt)));

            $this->seedTableHeader($table, $monthNumber);
            $this->seedTableRow($table,    'Дата оплаты');
            $this->seedTableRow($table,    DateTimeHelper::pronounce( $monthDate ));
            $this->seedTableRow($table,    'Сумма оплаты');
            $this->seedTableRow($table,    $this->user->getBenefit()->setCurrency($this->user->getCoursePaymentMethod())->format());
        }
        $this->wordDocument->setComplexBlock('table_monthes_prices', $table);
    }

    protected function seedTableHeader(&$table, $monthNumber)
    {
        $table->addRow();
        $table->addCell(self::CELL_WIDTH)->addText($monthNumber . ' месяц', self::F_STYLE_BOLD, self::P_STYLE);
        $table->addCell()->addText('Дата посещения',                          self::F_STYLE_BOLD, self::P_STYLE);
        $table->addCell()->addText('Пропуск по уважительной причине',         self::F_STYLE_BOLD, self::P_STYLE);
        $table->addCell()->addText('Перенос занятия по уважительной причине', self::F_STYLE_BOLD, self::P_STYLE);
        $table->addCell()->addText('Пропуск по неуважительной причине',       self::F_STYLE_BOLD, self::P_STYLE);
    }

    protected function seedTableRow(&$table, $text)
    {
        $table->addRow();
        $table->addCell(self::CELL_WIDTH)->addText($text, self::F_STYLE_BOLD, self::P_STYLE);
        for ($annexTableColumn = 0; $annexTableColumn < self::ANNEX_COLUMNS_COUNT; $annexTableColumn++) {
            $table->addCell()->addText('', self::F_STYLE_NORMAL, self::P_STYLE);
        }
    }

    protected function generateResultFileName(string $extension = self::DOCX): string
    {
        return self::RESULT_FOLDER . ($extension == self::PDF ? (self::SIGNED_PATH . '/') : '') . self::DOCUMENT_PREFIX . str_replace(' ', '_', $this->user->fullname) . '.' . $extension;
    }

    protected function attachFileToUser() 
    {
        $this->user->update([
            self::USER_COLUMN_PATH        => $this->resultFile,
            self::USER_COLUMN_PATH_SIGNED => $this->generateResultFileName(self::PDF)
        ]);
    }

    public static function uploadFile(Request $request) 
    {
        $request->file('file')->storeAs(
            str_replace('app/', '', self::RESULT_FOLDER . self::SIGNED_PATH . '/'), 
            basename(User::find($request->user_id)->contract_signed_path)
        );
    }
}