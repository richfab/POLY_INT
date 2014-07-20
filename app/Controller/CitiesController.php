<?php
/**
 * Cities Controller
 *
 * @property City $City
 * @property PaginatorComponent $Paginator
 */
App::uses('AppController', 'Controller');

/**
 * Cities Controller
 *
 * This class defines all actions relative to Cities
 *
 * @package		app.Controller
 */
class CitiesController extends AppController {

        /**
         * Components
         *
         * @var array
         */
	public $components = array('Paginator');
        
        /**
        * Pagination options
        *
        * @var array
        */
        public $paginate = array(
                'limit' => 20,
                'order' => array('lastname' => 'ASC'),
                'conditions' => array('User.role' => 'user','User.active' => '1')
        );

        /**
         * admin_index method
         *
         * @return void
         */
	public function admin_index() {
		$this->City->recursive = 0;
		$this->set('cities', $this->Paginator->paginate());
	}

        /**
         * admin_view method
         *
         * @throws NotFoundException
         * @param string $id
         * @return void
         */
	public function admin_view($id = null) {
                $this->City->recursive = 2;
		if (!$this->City->exists($id)) {
			throw new NotFoundException(__('Invalid city'));
		}
		$options = array('conditions' => array('City.' . $this->City->primaryKey => $id));
		$this->set('city', $this->City->find('first', $options));
	}

        /**
         * admin_add method
         *
         * @return void
         */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->City->create();
			if ($this->City->save($this->request->data)) {
				$this->Session->setFlash(__('The city has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The city could not be saved. Please, try again.'));
			}
		}
		$countries = $this->City->Country->find('list');
		$this->set(compact('countries'));
	}

        /**
         * admin_edit method
         *
         * @throws NotFoundException
         * @param string $id
         * @return void
         */
	public function admin_edit($id = null) {
		if (!$this->City->exists($id)) {
			throw new NotFoundException(__('Invalid city'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->City->save($this->request->data)) {
				$this->Session->setFlash(__('The city has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The city could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('City.' . $this->City->primaryKey => $id));
			$this->request->data = $this->City->find('first', $options);
		}
		$countries = $this->City->Country->find('list');
		$this->set(compact('countries'));
	}

        /**
         * admin_delete method
         *
         * @throws NotFoundException
         * @param string $id
         * @return void
         */
	public function admin_delete($id = null) {
		$this->City->id = $id;
		if (!$this->City->exists()) {
			throw new NotFoundException(__('Invalid city'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->City->delete()) {
			$this->Session->setFlash(__('The city has been deleted.'));
		} else {
			$this->Session->setFlash(__('The city could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
