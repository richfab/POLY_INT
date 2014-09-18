<?php
/**
 * Schools Controller
 *
 * @property School $School
 * @property PaginatorComponent $Paginator
 */
App::uses('AppController', 'Controller');

/**
 * Schools Controller
 *
 * This class defines all actions relative to Schools
 *
 * @package		app.Controller
 */
class SchoolsController extends AppController {

/**
 * Components
 *
 * @var array
 */
    public $components = array('Paginator','RequestHandler');
    
    
    /**
    * This method is called before the controller action. It is useful to define which actions are allowed publicly.
    *
    * @return void
    */
    public function beforeFilter() {
        parent::beforeFilter();
        //actions that anyone is allowed to call
        $this->Auth->allow(array('index')); 
    }
        
    public function index() {
        $schools = $this->School->find('all',
            array('recursive' => 0,
                    'fields' => array('id', 'name'), 
                    'order' => array('School.name')));
        $this->set(array(
            'schools' => $schools,
            '_serialize' => array('schools')
        ));
    }

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->School->recursive = 0;
		$this->set('schools', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->School->exists($id)) {
			throw new NotFoundException(__('Invalid school'));
		}
		$options = array('conditions' => array('School.' . $this->School->primaryKey => $id));
		$this->set('school', $this->School->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
            
                //on inclut le script jscolor pour le color picker
                $this->set('jsIncludes',array('jscolor'));
            
		if ($this->request->is('post')) {
			$this->School->create();
			if ($this->School->save($this->request->data)) {
				$this->Session->setFlash(__('The school has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The school could not be saved. Please, try again.'));
			}
		}
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
                //on inclut le script jscolor pour le color picker
                $this->set('jsIncludes',array('jscolor'));
            
		if (!$this->School->exists($id)) {
			throw new NotFoundException(__('Invalid school'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->School->save($this->request->data)) {
				$this->Session->setFlash(__('The school has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The school could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('School.' . $this->School->primaryKey => $id));
			$this->request->data = $this->School->find('first', $options);
		}
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->School->id = $id;
		if (!$this->School->exists()) {
			throw new NotFoundException(__('Invalid school'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->School->delete()) {
			$this->Session->setFlash(__('The school has been deleted.'));
		} else {
			$this->Session->setFlash(__('The school could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}
