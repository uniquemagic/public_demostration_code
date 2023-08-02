<?php 

namespace App\Services\Document;

use Illuminate\Http\Request;
use App\Models\User;
use Exception;

abstract class AbstractWordDocument
{
    private const TEMPLATE_FILE    = '';
    private const RESULT_FOLDER    = '';
    private const USER_COLUMN_PATH = '';
    private const PARAMS = [];
    private const DOCUMENT_PREFIX  = '';

    protected const F_STYLE_BOLD = [
        'size'  => 9,
        'bold'  => true,
        'name'  => 'Bookman Old Style',
        'align' => 'center',
    ];

    protected const F_STYLE_NORMAL = [
        'size'  => 9,
        'bold'  => false,
        'name'  => 'Bookman Old Style',
        'align' => 'center'
    ];

    protected const P_STYLE = [
        'align' => 'center'
    ];

    protected const CELL_WIDTH = 4000;

    protected const ANNEX_COLUMNS_COUNT = 4;

    protected const DOCX = 'docx';
    protected const PDF  = 'pdf';

    protected $wordDocument;
    protected $user;
    protected $contractSignedAt;
    protected $dataToSave;
    protected $resultFile;

    public function __construct(Request $request) 
    {
        $this->wordDocument         = $this->generateTemplate();
        $this->user                 = $this->getUser($request);
        $this->contractSignedAt     = $this->getContractSignedAt();
        $this->dataToSave           = $this->prepareDataToSave();
        $this->putSimpleDataToFile();
        $this->putTableDataToFile();
        $this->resultFile = $this->generateResultFileName();
        $this->attachFileToUser();
        $this->saveFile();
    }

    protected function getUser(Request $request)
    {
        return User::find($request->user_id);
    }

    protected function getContractSignedAt()
    {
        return $this->user->contract_signed_at ?? date('d.m.Y');
    }

    protected function saveFile() 
    {
        try {
            $this->wordDocument->saveAs(storage_path($this->resultFile));
        } 
        catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    protected function putSimpleDataToFile() 
    {
        foreach($this->dataToSave as $param => $value) {
            $this->wordDocument->setValue($param, $value);
        }
    }

    abstract protected function generateTemplate();
    abstract protected function prepareDataToSave(): array;
    abstract protected function generateResultFileName(string $extension = self::DOCX): string;
    abstract protected function attachFileToUser();
}