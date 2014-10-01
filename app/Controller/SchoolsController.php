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
	public $components = array('Paginator');
    
    /**
    * this methods returns all email domains in an array
    *
    **/
    public function get_email_domains(){
        
        $schools_email_domains = $this->School->find('list', array(
            'fields' => array('School.email_domains')
        ));
        $email_domains = array();
        
        foreach($schools_email_domains as $school_email_domains){
            
            $school_email_domains = explode(',', $school_email_domains);
            
            foreach($school_email_domains as $school_email_domain){
                if (preg_match('/^@/',$school_email_domain)) array_push($email_domains,$school_email_domain);
            }
        }
        
        return $email_domains;
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
