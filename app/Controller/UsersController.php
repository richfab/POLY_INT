<?php
App::uses('AppController', 'Controller');
    
class UsersController extends AppController {
    
    /* Set pagination options */
    public $paginate = array(
            'limit' => 20,
            'order' => array('created' => 'DESC'),
            'conditions' => array('User.role' => 'user','User.active' => '1')
    );
        
    /**
    * This method is called before the controller action. It is useful to define which actions are allowed publicly.
    *
    * @return void
    */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('signup','signup_request','activate','forgotten_password'); // Letting users signup themselves and retrieve password
    }
        
    /**
    * This method allows anyone to login
    *
    * @return void
    */
    public function login() {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                if($this->Auth->user('role')==='admin'){
                    return $this->redirect(array('controller' => 'dashboards', 'action' => 'index','admin'=>true));
                }
                //if user has activated his account then redirect to requested URL
                if($this->Auth->user('active')){
                    return $this->redirect($this->Auth->redirectUrl());
                }
                //else log user out
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
        
    /**
    * This method allows users to logout
    *
    * @return void
    */
    public function logout() {
        return $this->redirect($this->Auth->logout());
    }
        
    /**
    * This admin method allows administrator to accept a signup request done with a non verified email
    * 
    * @throws NotFoundException
    * @param string $id
    * @return void
    */
    public function admin_accept_request($id) {
        
        $this->User->create();
        $this->User->id = $id;
            
        $user = $this->User->findById($id);
        $user['User']['email'] = $user['User']['email_at_signup'];
            
        if (!$this->User->exists()) {
                throw new NotFoundException(__('Invalid user'));
        }
        $this->request->onlyAllow('post', 'accept_request');
            
        if($this->User->saveField('email',$user['User']['email'])){
                //sends activation email to user
                $this->__send_activation_email($user);
                $this->Session->setFlash(__('The user has been accepted. An activation email has been sent to the user.'));
        } else {
                $this->Session->setFlash(__('The user could not be accepted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'index'));
    }
        
    /**
    * This method allows anyone to signup with a verified email
    * 
    * @return void
    */
    public function signup() {
        
        //sets schools and departments by alphbetical order to populate select inputs
        $this->__set_schools_and_departments();
            
        if ($this->request->is('post')) {
            
            //checks that password and confirmation password are identical
            if (!($this->data['User']['password'] === $this->data['User']['password_confirm'])) {
                $this->Session->setFlash(__("Les mots de passe ne correspondent pas"), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));              
                return;
            }
                
            //TODO generate from database
            //list of verified emails
            $regexp_emails = "/@(etu.univ-nantes.fr|univ-nantes.fr|polytech-lille.net|etud.univ-montp2.fr|etu.univ-tours.fr|etu.univ-orleans.fr|polytech.upmc.fr|u-psud.fr|etudiant.univ-bpclermont.fr|etu.univ-lyon1.fr|etu.univ-savoie.fr|polytech.unice.fr|etu.univ-amu.fr|e.ujf-grenoble.fr)/";
                
            //checks that email belongs to list of verified emails
            if (!preg_match($regexp_emails,$this->data['User']['email'])){
                $this->Session->setFlash(__("Ton adresse étudiante est nécessaire pour l'inscription. Tu pourras la changer dans ton profil une fois inscrit."), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));              
                return;
            }
                
            $this->User->create();
            //user role is 'user'
            $this->request->data['User']['role'] = 'user';
            //stores email at signup to keep track of student email
            $this->request->data['User']['email_at_signup'] = $this->request->data['User']['email'];
                
            if ($this->User->save($this->request->data)) {
                
                //sends activation email
                $user = $this->User->findByEmail($this->request->data['User']['email']);
                $this->__send_activation_email($user);
                    
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
        
    /**
    * This method allows anyone to send a signup request with a non verified email
    * 
    * @return void
    */
    public function signup_request() {
        
        //sets schools and departments by alphbetical order to populate select inputs
        $this->__set_schools_and_departments();
            
        if ($this->request->is('post')) {
            
            if (!($this->data['User']['password'] === $this->data['User']['password_confirm'])) {
                $this->Session->setFlash(__("Les mots de passe ne correspondent pas"), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));              
                return;
            }
                
            $this->User->create();
            //on force le role a être user
            $this->request->data['User']['role'] = 'user';
                
            if ($this->User->save($this->request->data)) {
                
                App::uses('CakeEmail','Network/Email');
            	$user = $this->request->data;
                $email = new CakeEmail('default');
                $email->to($user['User']['email_at_signup'])
                        ->subject('Bienvenue sur Polytech Abroad !')
                        ->emailFormat('html')
                        ->template('signup_request')
                        ->viewVars(array('firstname' => $user['User']['firstname']))
                        ->send();
                            
                $this->Session->setFlash(__("Ta demande d'inscription a bien été prise en compte. Un email de confirmation sera bientôt envoyé à ".$user['User']['email_at_signup']), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                return $this->redirect(array('controller'=>'users','action' => 'login'));
            }
            $this->Session->setFlash(__("Erreur lors de la demande d'inscription"), 'alert', array(
                 'plugin' => 'BoostCake',
                 'class' => 'alert-danger'
             ));
        }
    }
        
    /**
    * This private method sends an activation email to a user
    *
    * @param string $user
    * @return void
    */
    private function __send_activation_email($user) {
        
        $activation_link = array('controller'=>'users', 'action' => 'activate','admin'=>false, $this->User->id.'-'.md5($user['User']['password']));
            
        App::uses('CakeEmail','Network/Email');
        $email = new CakeEmail('default');
        $email->to($user['User']['email'])
                ->subject('Bienvenue sur Polytech Abroad !')
                ->emailFormat('html')
                ->template('signup')
                ->viewVars(array('firstname' => $user['User']['firstname'],'activation_link' => $activation_link))
                ->send();
    }
        
    /**
    * This method allows user to activate his account with a token sent in activation email
    *
    * @param string $token
    * @return void
    */
    public function activate($token){
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
        
    /**
    * This method allows user to reinitialize his password from the link sent by email or to request reinitialization link
    *
    * @return void
    */
    public function forgotten_password(){
        
        //user cliked reinitialization link in email
        if(!empty($this->request->params['named']['token'])){
            
            $token = $this->request->params['named']['token'];
            $token = explode('-',$token);
                
            $user = $this->User->find('first',array('conditions'=> array('User.id'=>$token[0],'MD5(User.password)'=>$token[1])));
                
            if($user){
                
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
            
        //user requests reinitialization link
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
        
    /**
    * This method allows user to change his password
    *
    * @throws NotFoundException
    * @return void
    */
    public function change_password() {
        
        $id = $this->Auth->user('id');
        $this->User->id = $id;
            
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
            //checks that old password is correct
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
        
    /**
    * This method displays user profile (current user if user_id is null)
    *
    * @throws NotFoundException 
    * @param string $user_id
    * @return void
    */
    public function profile($user_id = null) {
        
        //includes scripts to send ajax recommendations and to display "read more" button on long posts
    	$this->set('jsIncludes',array('recommendations','readmore'));
            
        //user wants to see his own profile
        if($user_id==null){
            $user_id = $this->Auth->user('id');
        }
        //user wants to see someone else profile
        $this->User->id = $user_id;
            
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
        //gets recommendationtypes
        $this->set('recommendationtypes',$this->User->Experience->Recommendation->Recommendationtype->find('all'));
        //gets recommendationtypes by list
        $this->set('recommendationtypes_list',$this->User->Experience->Recommendation->Recommendationtype->find('list',array(
            'fields' => array('Recommendationtype.icon')
        )));
    }
        
    /**
    * This method allows user to edit his own information
    *
    * @throws NotFoundException 
    * @return void
    */
    public function edit() {
        
        $user_id = $this->Auth->user('id');
        $this->User->id = $user_id;
            
        //sets schools and departments by alphbetical order to populate select inputs
        $this->__set_schools_and_departments();
            
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
        
    /**
    * This private method sets schools and departments by alphbetical order to populate select inputs
    * 
    * @return void
    */
    private function __set_schools_and_departments() {
        //selects schools by alphabetical order
        $this->set('schools', $this->User->School->find('list', array(
                        'order' => array('School.name' => 'ASC'))));
        //selects departments by alphabetical order
        $this->set('departments', $this->User->Department->find('list', array(
                        'order' => array('Department.name' => 'ASC'))));
    }
        
    /**
    * This method allows user to delete his account
    *
    * @throws NotFoundException 
    * @return void
    */
    public function delete(){
        $this->request->onlyAllow('post');
            
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
    * This admin method displays all active users present in the database
    *
    * @return void
    */
    public function admin_index() {
        $this->Paginator->settings = $this->paginate;
        $this->User->recursive = 0;
        $this->set('user_requests', $this->User->find('all',array(
            'conditions' => array('email' => NULL)
        )));
        $this->set('users', $this->Paginator->paginate());
    }

    /**
     * This admin method displays the information of a user
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
     * This admin method loads the form view and adds a user to the database upon submit
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
     * This admin method loads the form view of a user and updates his information in the database upon submit
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
     * This admin method removes a user from the database and all his related experiences
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