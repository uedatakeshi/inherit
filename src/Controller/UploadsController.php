<?php
namespace App\Controller;

use App\Controller\AppController;
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
}
