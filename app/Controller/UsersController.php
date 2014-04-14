<?php
class UsersController extends AppController {
        
    /* Set pagination options */
    public $paginate = array(
            'limit' => 20,
            'order' => array('lastname' => 'ASC')
    );
        
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('signup','in_signup','forgotten_password'); // Letting users signup themselves and retrieve password
    }
	
    public function login() {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Session->setFlash(__("Mot de passe ou email incorrect"), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
        }
    }
	
    public function logout() {
        return $this->redirect($this->Auth->logout());
    }
	
    public function signup() {
        //selectionne les ecoles par ordre alphabetique
        $this->set('schools', $this->User->School->find('list', array(
                        'order' => array('School.name' => 'ASC'))));
        //selectionne les departements par ordre alphabetique
        $this->set('departments', $this->User->Department->find('list', array(
                        'order' => array('Department.name' => 'ASC'))));
                            
        if ($this->request->is('post')) {
            
            if (!($this->data['User']['password'] === $this->data['User']['password_confirm'])) {
                $this->Session->setFlash(__("Les mots de passe ne correspondent pas"), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));              
                return;
            }
                
            $this->User->create();
                
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__("Ton inscription a bien été prise en compte"), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                return $this->redirect(array('controller'=>'users','action' => 'login'));
            }
            $this->Session->setFlash(__("Erreur lors de l'inscription"), 'alert', array(
                 'plugin' => 'BoostCake',
                 'class' => 'alert-danger'
             ));
        }
    }
    
    public function in_signup() {
        //selectionne les ecoles par ordre alphabetique
        $this->set('schools', $this->User->School->find('list', array(
                        'order' => array('School.name' => 'ASC'))));
        //selectionne les departements par ordre alphabetique
        $this->set('departments', $this->User->Department->find('list', array(
                        'order' => array('Department.name' => 'ASC'))));
                            
        if ($this->request->is('post')) {
            
            if (!($this->data['User']['password'] === $this->data['User']['password_confirm'])) {
                $this->Session->setFlash(__("Les mots de passe ne correspondent pas"), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));              
                return;
            }
                
            $this->User->create();
                
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__("Ton inscription a bien été prise en compte"), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                return $this->redirect(array('controller'=>'users','action' => 'login'));
            }
            $this->Session->setFlash(__("Erreur lors de l'inscription"), 'alert', array(
                 'plugin' => 'BoostCake',
                 'class' => 'alert-danger'
             ));
        }
    }
        
    public function profile($user_id = null) {
    	App::uses('AuthComponent', 'Controller/Component');
            
        if($this->Auth->loggedIn()){
            
            //si l'utilisateur veut voir son propre profile
            if($user_id==null){
                $user_id = $this->Auth->user('id');
            }
           //si l'utilisateur cherche a voir le profile de quelqu'un d'autre
            $this->User->id = $user_id;
                
        }
        else{
            return $this->redirect(array('controller'=>'users', 'action' => 'login'));
        }
            
        if (!$this->User->exists()) {
            throw new NotFoundException(__("Cet utilisateur n'existe pas"));
        }
        $this->set('user',$this->User->read(null, $user_id));
        $this->set('experiences',$this->User->Experience->find('all', array(
            'conditions' => array('user_id' => $user_id),
            'order' => array('dateEnd' => 'DESC'),
            'recursive' => 2
        )));
    }
        
    public function edit() {
    	App::uses('AuthComponent', 'Controller/Component');
            
        if($this->Auth->loggedIn()){
            $user_id = $this->Auth->user('id');
            $this->User->id = $user_id;
        }
        else{
            return $this->redirect(array('action' => 'login'));
        }
            
        //selectionne les ecoles par ordre alphabetique
        $this->set('schools', $this->User->School->find('list', array(
                        'order' => array('School.name' => 'ASC'))));
        //selectionne les departements par ordre alphabetique
        $this->set('departments', $this->User->Department->find('list', array(
                        'order' => array('Department.name' => 'ASC'))));
                            
        if (!$this->User->exists()) {
            throw new NotFoundException(__("Cet utilisateur n'existe pas"));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__("Les modifications ont bien été enregistrées"), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                return $this->redirect(array('action' => 'profile'));
            }
            $this->Session->setFlash(__("Erreur lors de l'enregistrement"), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
        } else {
            $this->request->data = $this->User->read(null, $user_id);
            unset($this->request->data['User']['password']);
        }
    }
        
    public function delete(){
        $this->request->onlyAllow('post');
            
        App::uses('AuthComponent', 'Controller/Component');
        $user_id = $this->Auth->user('id');
            
        $user = $this->User->findById($user_id);
        $experiences = $user['Experience'];
            
        App::import('Controller', 'Experiences');
        $experiencesController = new ExperiencesController;
            
        foreach($experiences as $experience){
            $experiencesController->delete_experience($experience['id']);
        }
            
        $this->User->id = $user_id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__("Le compte n'éxiste plus"));
        }
        if ($this->User->delete()) {
            $this->Session->setFlash(__("Le compte a bien été supprimé"), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            ));
            return $this->redirect(array('action' => 'logout'));
        }
        $this->Session->setFlash(__("Le compte n'a pas pu être supprimé"), 'alert', array(
            'plugin' => 'BoostCake',
            'class' => 'alert-danger'
        ));
        return $this->redirect($this->referer());
    }
}
?>