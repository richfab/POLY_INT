<?php
class UsersController extends AppController {
    
    /* Load the paginator helper for use */
    public $helpers = array('Paginator' => array('Paginator'));
        
    /* Set pagination options */
    public $paginate = array(
            'limit' => 20,
            'order' => array('lastname' => 'ASC')
    );
        
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('signup','forgotten_password'); // Letting users signup themselves and retrieve password
    }
	
    public function login() {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                return $this->redirect($this->Auth->redirect());
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
                $this->Session->setFlash(__("Ton inscription a bien été prise en compte. Un email de confirmation vient de t'être envoyé"), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                return $this->redirect(array('controller'=>'pages','action' => 'home'));
            }
            $this->Session->setFlash(__("Erreur lors de l'inscription"), 'alert', array(
                 'plugin' => 'BoostCake',
                 'class' => 'alert-danger'
             ));
        }
    }
	
//    public function forgotten_password(){
//        if(!empty($this->request->params['named']['token'])){
//            $token = $this->request->params['named']['token'];
//            $token = explode('-',$token);
//            $user = $this->User->find('first',array('conditions'=>array('User.id'=>$token[0],'MD5(User.password)'=>$token[1])));
//            if($user){
//                App::uses('AuthComponent', 'Controller/Component');
//                    
//                $this->User->id = $user['User']['id'];
//                $password = substr(md5(uniqid(rand(),true)),0,8);
//                $this->User->saveField('password',$password);
//                $this->Session->setFlash("Voici votre nouveau mot de passe : $password. Vous pouvez le changer dans votre compte.");
//            }
//            else{
//                $this->Session->setFlash("Le lien n'est pas valide");
//            }
//        }
//            
//        if ($this->request->is('post')) {
//            $user = $this->User->find('first',array('conditions'=>array('email'=>$this->request->data['User']['email'])));
//            if(!$user){
//                    $this->Session->setFlash("Aucun utilisateur ne correspond à cet email");
//            }
//            else{
//                App::uses('CakeEmail','Network/Email');
//                $link = array('controller'=>'users','action'=>'password','token'=>$user['User']['id'].'-'.md5($user['User']['password']));
//                $email = new CakeEmail('default');
//                $email->to($user['User']['email'])
//                        ->subject('Nouveau mot de passe')
//                        ->emailFormat('html')
//                        ->template('password')
//                        ->viewVars(array('firstname'=>$user['User']['firstname'],'link'=>$link))
//                        ->send();
//                $this->Session->setFlash("Un email avec un lien pour réinitialiser votre mot de passe vient de vous être envoyé");
//            }
//        }
//    }
        
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