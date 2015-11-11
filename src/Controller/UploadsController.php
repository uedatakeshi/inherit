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
        $options = array(
            'upload_dir' => WWW_ROOT . 'files/' . date("Ymd") . "/",        
            'accept_file_types' => '/\.(pdf|gif|jpe?g|png)$/i'                     
           );
        $uploadHandler = new MyUploadHandler($options);
    }

}
