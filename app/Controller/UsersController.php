<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController {
    
    /* Set pagination options */
    public $paginate = array(
            'limit' => 20,
            'order' => array('lastname' => 'ASC'),
            'conditions' => array('User.role' => 'user','User.active' => '1')
    );
        
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('signup','activate','forgotten_password'); // Letting users signup themselves and retrieve password
    }
	
    public function login() {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                if($this->Auth->user('role')==='admin'){
                    return $this->redirect(array('controller'=>'users','action' => 'index','admin'=>true));
                }
                //si le user a activé son compte
                if($this->Auth->user('active')){
                    return $this->redirect($this->Auth->redirectUrl());
                }
                else{
                    $this->Session->setFlash(__("Tu dois d'abord activer ton compte à l'aide du lien d'activation qui t'a été envoyé par email (cela peut prendre un peu de temps)"), 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-danger'
                    ));
                    $this->Auth->logout();
                    return $this->redirect(array('controller'=>'users','action' => 'login'));
                }
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
            
            //TODO générer a partir de la BDD
            $regexp_emails = "/@(etu.univ-nantes.fr|univ-nantes.fr|polytech-lille.net|etud.univ-montp2.fr|etu.univ-tours.fr|etu.univ-orleans.fr|polytech.upmc.fr|u-psud.fr|etudiant.univ-bpclermont.fr|etu.univ-lyon1.fr|etu.univ-savoie.fr|polytech.unice.fr|etu.univ-amu.fr|e.ujf-grenoble.fr)/";
            
            if (!preg_match($regexp_emails,$this->data['User']['email'])){
                $this->Session->setFlash(__("Ton adresse étudiante est nécessaire pour l'inscription. Tu pourras la changer dans ton profil une fois inscrit."), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));              
                return;
            }
                
            $this->User->create();
            //on force le role a être user
            $this->request->data['User']['role'] = 'user';
                
            if ($this->User->save($this->request->data)) {
                
                $user = $this->User->findByEmail($this->request->data['User']['email']);
                $activation_link = array('controller'=>'users', 'action' => 'activate',  $this->User->id.'-'.md5($user['User']['password']));
                
                App::uses('CakeEmail','Network/Email');
            	$user = $this->request->data;
                $email = new CakeEmail('default');
                $email->to($user['User']['email'])
                        ->subject('Bienvenue sur Polytech Abroad !')
                        ->emailFormat('html')
                        ->template('signup')
                        ->viewVars(array('firstname' => $user['User']['firstname'],'activation_link' => $activation_link))
                        ->send();
                                
                $this->Session->setFlash(__("Ton inscription a bien été prise en compte. Un email d'activation sera bientôt envoyé à ".$user['User']['email']), 'alert', array(
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
    
    //fonction qui permet a partir d'un lien, d'activer son compte
    public function activate($token = null){
        $token = explode('-', $token);
        $user = $this->User->find('first', array(
            'conditions' => array('User.id' => $token[0], 'MD5(User.password)' => $token[1], 'active' => 0)
        ));
        if(!empty($user)){
            $this->User->id = $user['User']['id'];
            $this->User->saveField('active',1);
            $this->Session->setFlash(__("Ton compte a bien été activé"), 'alert', array(
                 'plugin' => 'BoostCake',
                 'class' => 'alert-success'
             ));
            $this->Auth->login($user['User']);
            return $this->redirect(array('controller'=>'users','action' => 'profile'));
        }
        else{
            $this->Session->setFlash(__("Ce lien d'activation n'est pas valide"), 'alert', array(
                 'plugin' => 'BoostCake',
                 'class' => 'alert-danger'
             ));
            return $this->redirect(array('controller'=>'users','action' => 'login'));
        }
        
    }
    
    //fonction qui permet de recuperer son nouveau mot de passe a partir du lien recu par email ou qui permet de saisir son email en cas de mot de passe oublié
    public function forgotten_password(){
        
        if(!empty($this->request->params['named']['token'])){
            
            $token = $this->request->params['named']['token'];
            $token = explode('-',$token);
            
            $user = $this->User->find('first',array('conditions'=> array('User.id'=>$token[0],'MD5(User.password)'=>$token[1])));
            
            if($user){
                App::uses('AuthComponent', 'Controller/Component');

                $this->User->id = $user['User']['id'];
                
                $password = substr(md5(uniqid(rand(),true)),0,8);
                $this->User->saveField('password',$password);
                
                $this->Session->setFlash(__("Voici ton nouveau mot de passe : $password. Il est conseillé de le changer dans les paramêtres de ton profil"), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
            }
            else{
                $this->Session->setFlash(__("Ce lien n'est pas valide"), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));
            }
        }

        if($this->request->is('post')) {
            $user = $this->User->findByEmail($this->request->data['User']['email']);
            if(!$user){
                $this->Session->setFlash(__("Aucun utilisateur ne correspond à cet email"), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));
            }
            else{
                App::uses('CakeEmail','Network/Email');
                
                $link = array('controller'=>'users','action'=>'forgotten_password','token'=>$user['User']['id'].'-'.md5($user['User']['password']));
                
                $email = new CakeEmail('default');
                $email->to($user['User']['email'])
                        ->subject('Réinitialisation mot de passe')
                        ->emailFormat('html')
                        ->template('forgotten_password')
                        ->viewVars(array('firstname'=>$user['User']['firstname'],'link'=>$link))
                        ->send();
                
                $this->Session->setFlash(__("Un email avec un lien pour réinitialiser le mot de passe vient d'être envoyé à ".$user['User']['email']), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
            }
        }
    }
    
    //page qui peut etre appelée a partir de la page profil et qui permet de changer son mot de passe
    public function change_password() {
    	App::uses('AuthComponent', 'Controller/Component');
		
        if($this->Auth->loggedIn()){
            $id = $this->Auth->user('id');
            $this->User->id = $id;
        }
        else{
            return $this->redirect(array('action' => 'login'));
        }
        if (!$this->User->exists()) {
            throw new NotFoundException(__("Cet utilisateur n'existe pas"));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if (!($this->data['User']['password'] === $this->data['User']['password_confirm'])) {
                $this->Session->setFlash(__("Les mots de passe ne correspondent pas"), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));
                return;
            }
            //on verifie que l'ancien mot de passe correspond
            $user = $this->User->findById($id);
            if(AuthComponent::password($this->request->data['User']['old_password']) === $user['User']['password']){
            
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
            }
            else{
                $this->Session->setFlash(__("L'ancien mot de passe est incorrect"), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));
            }
        }
        else{
            $this->request->data = $this->User->read(null, $id);
            unset($this->request->data['User']['password']);
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
        
        //on inclut les scripts pour l'envoi des recommandations en ajax
    	$this->set('jsIncludes',array('recommendations','readmore'));
            
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
        $this->set('countries',$this->User->Experience->City->Country->find('list'));
        $this->set('experiences',$this->User->Experience->find('all', array(
            'conditions' => array('user_id' => $user_id),
            'order' => array('dateEnd' => 'DESC'),
            'recursive' => 1
        )));
        //recupere les recommendationtypes
        $this->set('recommendationtypes',$this->User->Experience->Recommendation->Recommendationtype->find('all'));
        //recupere les recommendationtypes par liste
        $this->set('recommendationtypes_list',$this->User->Experience->Recommendation->Recommendationtype->find('list',array(
            'fields' => array('Recommendationtype.icon')
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
    
    
    //admin methods//
    /**
 
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
                $this->Paginator->settings = $this->paginate;
		$this->User->recursive = 0;
		$this->set('users', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$this->set('user', $this->User->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		}
		$schools = $this->User->School->find('list');
		$departments = $this->User->Department->find('list');
		$this->set(compact('schools', 'departments'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
		}
		$schools = $this->User->School->find('list');
		$departments = $this->User->Department->find('list');
		$this->set(compact('schools', 'departments'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->User->id = $id;
                
                $user = $this->User->findById($id);
                $experiences = $user['Experience'];

                App::import('Controller', 'Experiences');
                $experiencesController = new ExperiencesController;

                foreach($experiences as $experience){
                    $experiencesController->delete_experience($experience['id']);
                }
                
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->User->delete()) {
			$this->Session->setFlash(__('The user has been deleted.'));
		} else {
			$this->Session->setFlash(__('The user could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
}
?>