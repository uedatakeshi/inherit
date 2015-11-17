<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\View\Exception\MissingTemplateException;
use MyUploadHandler;

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
        //$this->loadComponent('RequestHandler');
        $this->loadComponent('Upload');
    }

    /**
     * index method
     *
     * @return void
     */
    public function index()
    {
        $this->autoRender = false;
        $options = [
            'upload_dir' => WWW_ROOT . 'files/' . date("Ymd") . "/",
            'accept_file_types' => '/\.(pdf|gif|jpe?g|png)$/i'
        ];
        $uploadHandler = new MyUploadHandler($options);
    }

    /**
     * upload method
     *
     * @return void
     */
    public function upload()
    {
        $this->autoRender = false;
        $options = [
            'upload_dir' => WWW_ROOT . 'files/' . date("Ymd") . "/",
            'accept_file_types' => '/\.(pdf|gif|jpe?g|png)$/i'
        ];
        $this->Upload->send($options);
    }
}
