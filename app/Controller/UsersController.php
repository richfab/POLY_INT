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
            $this->Session->setFlash("Mot de passe ou email incorrect");
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
                $this->Session->setFlash("Les mots de passe ne correspondent pas");               
                return;
            }
			
            $this->User->create();
            
            if ($this->User->save($this->request->data)) {
		$this->Session->setFlash("Votre inscription a bien été prise en compte. Un email de confirmation vient de vous être envoyé");
                return $this->redirect(array('controller'=>'pages','action' => 'home'));
            }
            
            $this->Session->setFlash("Erreur lors de l'inscription");
        }
    }
	
    public function forgotten_password(){
        if(!empty($this->request->params['named']['token'])){
            $token = $this->request->params['named']['token'];
            $token = explode('-',$token);
            $user = $this->User->find('first',array('conditions'=>array('User.id'=>$token[0],'MD5(User.password)'=>$token[1])));
            if($user){
                App::uses('AuthComponent', 'Controller/Component');

                $this->User->id = $user['User']['id'];
                $password = substr(md5(uniqid(rand(),true)),0,8);
                $this->User->saveField('password',$password);
                $this->Session->setFlash("Voici votre nouveau mot de passe : $password. Vous pouvez le changer dans votre compte.");
            }
            else{
                $this->Session->setFlash("Le lien n'est pas valide");
            }
        }

        if ($this->request->is('post')) {
            $user = $this->User->find('first',array('conditions'=>array('email'=>$this->request->data['User']['email'])));
            if(!$user){
                    $this->Session->setFlash("Aucun utilisateur ne correspond à cet email");
            }
            else{
                App::uses('CakeEmail','Network/Email');
                $link = array('controller'=>'users','action'=>'password','token'=>$user['User']['id'].'-'.md5($user['User']['password']));
                $email = new CakeEmail('default');
                $email->to($user['User']['email'])
                        ->subject('Nouveau mot de passe')
                        ->emailFormat('html')
                        ->template('password')
                        ->viewVars(array('firstname'=>$user['User']['firstname'],'link'=>$link))
                        ->send();
                $this->Session->setFlash("Un email avec un lien pour réinitialiser votre mot de passe vient de vous être envoyé");
            }
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

    public function admin_index($year_id = null) {
        $this->User->recursive = 0;
        $this->set('users', $this->Paginate('User',array('role' => 'user')));
    }
}
?>