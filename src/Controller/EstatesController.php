<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Log\Log;
use Cake\Mailer\Email;

/**
 * Estates Controller
 *
 * @property \App\Model\Table\EstatesTable $Estates
 */
class EstatesController extends AppController
{
    /**
     * initialize method
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
    }
    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('estates', $this->paginate($this->Estates));
        $this->set('_serialize', ['estates']);
    }

    /**
     * View method
     *
     * @param string|null $id Estate id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $estate = $this->Estates->get($id, [
            'contain' => []
        ]);
        $this->set('estate', $estate);
        $this->set('_serialize', ['estate']);
    }

    /**
     * Add method
     *
     * @return mixed Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $estate = $this->Estates->newEntity();
        if ($this->request->is('post')) {
            $estate = $this->Estates->patchEntity($estate, $this->request->data);
            if ($this->Estates->save($estate)) {
                $this->Flash->success(__('The estate has been saved.'));

                $email = new Email();
                $email->transport('default');
                $email->from(['develop996bn@yahoo.co.jp' => 'My Site'])
                    ->to('uedatakeshi@gmail.com')
                    ->subject('About')
                    ->send('My message');


                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The estate could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('estate'));
        $this->set('_serialize', ['estate']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Estate id.
     * @return mixed Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $estate = $this->Estates->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $estate = $this->Estates->patchEntity($estate, $this->request->data);
            if ($this->Estates->save($estate)) {
                $this->Flash->success(__('The estate has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The estate could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('estate'));
        $this->set('_serialize', ['estate']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Estate id.
     * @return mixed Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $estate = $this->Estates->get($id);
        if ($this->Estates->delete($estate)) {
            $this->Flash->success(__('The estate has been deleted.'));
        } else {
            $this->Flash->error(__('The estate could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
