<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\View\Exception\MissingTemplateException;
use UploadHandler;

/**
 * Uploads Controller
 *
 */
class UploadsController extends AppController
{
    /**
     * initialize method
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }

    /**
     * index method
     *
     * @return void
     */
    public function index()
    {
        $this->autoRender = false;
        $uploadHandler = new UploadHandler();
    }

    // 手動でアップロード実装
    public function add()
    {
        $this->request->data['submittedfile'] = [
            'name' => 'conference_schedule.pdf',
            'type' => 'application/pdf',
            'tmp_name' => 'C:/WINDOWS/TEMP/php1EE.tmp',
            'error' => 0, // On Windows this can be a string.
            'size' => 41737,
        ];
    }

}
