<?php
App::uses('AppController', 'Controller');
    
class ExperiencesController extends AppController {
    
    public $helpers = array('Image.Image');
    
    //pour l'extension json
    public $components = array("RequestHandler");
        
    /* Set pagination options */
    public $paginate = array(
            'limit' => 20,
            'order' => array('dateEnd' => 'DESC'),
            'conditions' => array('User.active'=>'1')
    );
        
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('explore','get_map_init','get_map','get_experiences','search')); //ce que tout le monde a le droit de faire
    }
        
    public function info($experience_id = null){
    	App::uses('AuthComponent', 'Controller/Component');
            
        if($experience_id != null){
            $this->Experience->id = $experience_id;
            if (!$this->Experience->exists()) {
                throw new NotFoundException(__("Cette experience n'existe plus"));
            }
        }
            
        //selectionne les motifs par ordre alphabetique
        $this->set('motives', $this->Experience->Motive->find('list', array(
                        'order' => array('Motive.name' => 'ASC'))));
                            
        //selectionne les motifs par ordre alphabetique
        $this->set('typenotifications', $this->Experience->Typenotification->find('list', array(
                        'order' => array('Typenotification.id' => 'DESC'))));
                            
        //on inclut le script google maps pour l'autocomplete des lieux
    	$this->set('jsIncludes',array('http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=fr&libraries=places','places_autocomplete'));
            
        $user_id = $this->Auth->user('id');
        $this->request->data['Experience']['user_id'] = $user_id;
            
        if ($this->request->is('post') || $this->request->is('put')) {
            
            //si c'est une modification d'experience, on decremente l'ancienne ville
            if($experience_id != null){
                $experience = $this->Experience->findById($experience_id);
                $this->_upload_experienceNumber($experience['Experience']['city_id'],-1);
            }
                
            //on renseigne l'id de la ville de l'experience
            $city_id = $this->_createCityAndCountryIfNeeded($this->request->data, $this->request->data['City']);
            $this->request->data['Experience']['city_id'] = $city_id;
                
            //si c'est une modification d'experience on renseigne l'id
            if($experience_id != null){
                $this->request->data['Experience']['id'] = $experience_id;
            }
            
            //l'utilisateur essaie d'ajouter une expérience en France
            if($this->request->data['City']['Country']['id'] === 'FR'){
                $this->Session->setFlash(__("Désolé, les expériences en France ne sont pas affichées sur Polytech Abroad"), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));
                return $this->redirect($this->referer());
            }
        	
            $this->Experience->create();
                
            if ($this->Experience->save($this->request->data) && $this->_upload_experienceNumber($city_id,1)) {
                
                $experience_id = $this->Experience->id;
                $experience = $this->Experience->findById($experience_id);
                    
                $this->Session->setFlash(__("Les modifications ont bien été enregistrées"), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
                return $this->redirect(array('controller'=>'users', 'action' => 'profile'));             
            }
            $this->Session->setFlash(__("Erreur lors de l'enregistrement"), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-danger'
            ));
        }
        else{
            $this->request->data = $this->Experience->find('first', array('conditions' => array('Experience.id' => $experience_id), 'recursive' => 1));
            //recupere les pays
            $this->set('countries',$this->Experience->City->Country->find('list'));
        }
    }
        
    public function explore(){
        //on inclut les scripts pour la recuperation des experiences
    	$this->set('jsIncludes',array('get_experiences','logo_fly','jvector','jquery-jvectormap.min','jquery-jvectormap-world-mill-en','jquery.dropdown'));
        //on inclut les style pour la carte
        $this->set('cssIncludes',array('jvectormap','map','filter'));
            
        //selectionne les motifs par ordre alphabetique
        $this->set('motives', $this->Experience->Motive->find('list', array(
                        'order' => array('Motive.name' => 'ASC'))));
        //selectionne les ecoles par ordre alphabetique
        $this->set('schools', $this->Experience->User->School->find('list', array(
                        'order' => array('School.name' => 'ASC'))));
        //selectionne les departements par ordre alphabetique
        $this->set('departments', $this->Experience->User->Department->find('list', array(
                        'order' => array('Department.name' => 'ASC'))));
    }
        
    //cette fonction retourne l'id de la ville, qu'elle ait été créée ou non
    protected function _createCityAndCountryIfNeeded($city_input = null, $country_input = null){
        
        //etape 1 : on teste si la ville de ce pays existe deja dans la base
        $city = $this->Experience->City->find('first', array(
            'conditions' => array('City.name' => $city_input['City']['name']),
            'recursive' => 0
        ));
        //si on a trouvé une ville de ce nom
        if(!empty($city)){
            $country = $this->Experience->City->Country->findById($city['City']['country_id']);
        }
            
        //si la ville de ce pays n'existe pas dans la bdd
        if(empty($country)){
            
            //etape 2 : on teste si le pays entré existe deja dans la bdd
            $country = $this->Experience->City->Country->findById($country_input['Country']['id']);
                
            //si le pays n'existe pas dans la bdd, on le creer
            if(empty($country)){
                $this->request->data['Country'] = $country_input['Country'];
                $this->Experience->City->Country->create();
                $country = $this->Experience->City->Country->save($this->request->data);
            }
                
            //puis on creer la ville
            $this->request->data['City'] = $city_input['City'];
            $this->request->data['City']['country_id'] = $country['Country']['id'];
            $this->Experience->City->create();
            $city = $this->Experience->City->save($this->request->data);
        }
            
        return $city['City']['id'];
    }
        
    public function delete($experience_id = null) {
        $this->request->onlyAllow('post');
            
        $experience = $this->Experience->findById($experience_id);
            
        //on verifie que l'experience est bien celle de l'utilisateur connecté
        App::uses('AuthComponent', 'Controller/Component');
        $user_id = $this->Auth->user('id');
        if($experience['Experience']['user_id'] != $user_id){
            //l'utilisateur essaie de modifier une experience qui n'est pas la sienne
            return $this->redirect(array('controller'=>'users', 'action' => 'login'));
        }
            
        $this->Experience->id = $experience['Experience']['id'];
            
        if (!$this->Experience->exists()) {
            throw new NotFoundException(__("Cette experience n'existe plus"));
        }
        if ($this->delete_experience($experience['Experience']['id'])) {
            $this->Session->setFlash(__("L'expérience a bien été supprimée"), 'alert', array(
                'plugin' => 'BoostCake',
                'class' => 'alert-success'
            ));
            return $this->redirect($this->referer());
        }
        $this->Session->setFlash(__("L'expérience n'a pas pu être supprimée"), 'alert', array(
            'plugin' => 'BoostCake',
            'class' => 'alert-danger'
        ));
        return $this->redirect($this->referer());
    }
        
    public function delete_experience($experience_id = null) {
        
        $experience = $this->Experience->findById($experience_id);
            
        $this->Experience->id = $experience['Experience']['id'];
            
        if (!$this->Experience->exists()) {
            return false;
        }
        if ($this->Experience->delete() && $this->_upload_experienceNumber($experience['Experience']['city_id'],-1)) {
            return true;
        }
        return false;
    }
        
    public function get_map_init(){
//        $this->request->onlyAllow('ajax');
        $this->set('countries', $this->Experience->City->Country->find('all',array(
            'conditions' => array('Country.experienceNumber >' => 0)
        )));
    }
        
    public function get_map(){
        
        $this->request->onlyAllow('ajax');
            
        //on transforme l'objet de parametres en conditions
        $conditions = $this->_filters_to_conditions($this->request->data);
            
         //on recupere le nombre d'experiences par ville
        $this->set('cities', $this->Experience->find('all', array(
                    'conditions' => $conditions,
                    'fields' => array('City.id','City.name','City.lat','City.lon','(COUNT(*)) as experienceNumber'),
                    'group' => 'Experience.city_id')));
                        
        //on recupere le nombre d'experiences par pays
        $this->set('countries', $this->Experience->find('all', array(
                    'conditions' => $conditions,
                    'fields' => array('City.country_id','(COUNT(*)) as experienceNumber'),
                    'group' => 'City.country_id')));
    }
        
    public function search(){
        //on inclut les scripts pour la recuperation des experiences et google maps pour l'autocomplete des lieux
    	$this->set('jsIncludes',array('http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=fr&libraries=places','places_autocomplete','get_experiences','logo_fly','jquery.dropdown'));
        //on inclut les style pour les filtres
        $this->set('cssIncludes',array('filter'));
            
        //selectionne les motifs par ordre alphabetique
        $this->set('motives', $this->Experience->Motive->find('list', array(
                        'order' => array('Motive.name' => 'ASC'))));
        //selectionne les ecoles par ordre alphabetique
        $this->set('schools', $this->Experience->User->School->find('list', array(
                        'order' => array('School.name' => 'ASC'))));
        //selectionne les departements par ordre alphabetique
        $this->set('departments', $this->Experience->User->Department->find('list', array(
                        'order' => array('Department.name' => 'ASC'))));
    }
        
    public function get_experiences(){
        
        $this->request->onlyAllow('ajax');
        App::uses('AuthComponent', 'Controller/Component');
            
         //on recupere les experiences si l'utilisateur est connecté
        if($this->Auth->user('id')){
            //on transforme l'objet de parametres en conditions
            $conditions = $this->_filters_to_conditions($this->request->data);
            
            //on definit la limite du nombre de resultats
            $offset = $this->request->data['offset'];
            $result_limit = 20;
            
            $this->set('result_limit',$result_limit);
            $this->set('offset',$offset);
            
            $this->set('experiences', $this->Experience->find('all', array(
                        'conditions' => $conditions,
                        'recursive' => 1,
                        //on ordonne par rapport a la date dateSort calculée par "fields"
                        'order' => 'dateSort ASC',
                        'limit' => $result_limit,
                        'offset' => $offset,
                        'fields' => array('*',
                            //si l'experience est passée, on ajoute le nombre de jours entre la date de fin et aujourd'hui a une très grande date pour que ces expériences se retrouvent a la fin de la liste, sinon on prend simplement la date de debut
                            'IF(DATEDIFF(Experience.dateEnd, NOW()) < 0,DATE_ADD("2200-01-01",INTERVAL ABS(DATEDIFF(Experience.dateEnd,NOW())) DAY),Experience.dateStart) AS dateSort',
                            'DATEDIFF(Experience.dateEnd, Experience.dateStart)/30 monthDiff'))));
            
            //recupere les pays
            $this->set('countries',$this->Experience->City->Country->find('list'));
            //recupere les departements
            $this->set('departments',$this->Experience->User->Department->find('list'));
            //recupere les ecoles
            $this->set('school_names',$this->Experience->User->School->find('list'));
            $this->set('school_colors',$this->Experience->User->School->find('list',array(
                                                                'fields' => array('School.color'))));
        }
        $this->render('/Experiences/'.$this->request->data['view_to_render']);
    }
    
    //fonction utile a l'ajout d'experience a partir d'une base de donnees comme celle de echarlemagne par exemple
    public function echarlemagne(){
        //on inclut les scripts pour la recuperation des experiences et google maps pour l'autocomplete des lieux
    	$this->set('jsIncludes',array('http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=fr&libraries=places','add_experiences'));
    }
    
    //fonction utile a l'ajout d'experience a partir d'une base de donnees comme celle de echarlemagne par exemple
    public function add_experience_ajax(){
        
        $this->request->onlyAllow('ajax');
        App::uses('AuthComponent', 'Controller/Component');
            
         //on recupere les experiences si c'est un utilisateur admin qui est connecté
        if($this->Auth->user('id') && $this->Auth->user('role') == 'admin'){
            
            //on recherche l'email de l'etudiant pour savoir s'il a deja un compte
            $user = $this->Experience->User->findByEmail($this->request->data['email']);
                
            //l'etudiant n'existe pas -> on le créer
            if(empty($user)){
                $this->Experience->User->create();
                $user['User']['email'] = $this->request->data['email'];
                $user['User']['firstname'] = $this->request->data['firstname'];
                $user['User']['lastname'] = $this->request->data['lastname'];
                $user['User']['school_id'] = $this->request->data['school_id'];
                $user['User']['department_id'] = $this->request->data['department_id'];
                $user['User']['active'] = "1"; //TODO active = 0
                $user = $this->Experience->User->save($user);
            }
                
            //ajout de la ville si besoin
            $city = array();
            $city['City']['name'] = $this->request->data['city_name'];
            $city['City']['lat'] = $this->request->data['city_lat'];
            $city['City']['lon'] = $this->request->data['city_lon'];
            $city['City']['Country']['id'] = $this->request->data['country_id'];
            $city['City']['Country']['name'] = $this->request->data['country_name'];
            $city_id = $this->_createCityAndCountryIfNeeded($city, $city['City']);
                
            //puis ajout de l'experience
            $this->Experience->create();
            $experience = array();
            $experience['Experience']['dateStart'] = $this->request->data['dateStart'];
            $experience['Experience']['dateEnd'] = $this->request->data['dateEnd'];
            $experience['Experience']['establishment'] = $this->request->data['establishment'];
            $experience['Experience']['description'] = $this->request->data['description'];
            $experience['Experience']['comment'] = $this->request->data['comment'];
            $experience['Experience']['city_id'] = $city_id;
            $experience['Experience']['motive_id'] = $this->request->data['motive_id'];
            $experience['Experience']['user_id'] = $user['User']['id'];
            $experience['Experience']['typenotification_id'] = $this->request->data['typenotification_id'];
                
            if($this->Experience->save($experience) && $this->_upload_experienceNumber($city_id,1)){
                return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>0)),'status'=>200));
            }
                
            return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>"Erreur lors de l'ajout")),'status'=>500));
        }
        return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>"Vous n'êtes pas admin")),'status'=>500));
    }
        
    //fonction qui transforme l'objet de parametres en conditions pour le find
    protected function _filters_to_conditions($request_data = null){
        
        $conditions = array();
        //on ne recupere que les experiences dont l'etudiant a été validé
        $conditions['User.active'] = 1;
            
        if(empty($request_data)){
            return $conditions;
        }
            
        if(!empty($request_data['motive_id'])){
            $conditions['Experience.motive_id'] = $request_data['motive_id'];
        }
        if(!empty($request_data['department_id'])){
            $conditions['User.department_id'] = $request_data['department_id'];
        }
        if(!empty($request_data['school_id'])){
            $conditions['User.school_id'] = $request_data['school_id'];
        }
        if(!empty($request_data['key_word'])){
            $conditions['Experience.description LIKE'] = '%'.$request_data['key_word'].'%';
        }
        //maintenant
        if(!empty($request_data['date_min']) && !empty($request_data['date_max']) && ($request_data['date_min'] === $request_data['date_max'])){
            $conditions['AND'] = array('Experience.dateEnd >=' => $request_data['date_min'],
                'Experience.dateStart <=' => $request_data['date_max']);
        }
        else{
            if(!empty($request_data['date_min'])){
                $conditions['Experience.dateStart >='] = $request_data['date_min'];
            }
            if(!empty($request_data['date_max'])){
                $conditions['Experience.dateStart <='] = $request_data['date_max'];
            }
        }
        if(!empty($request_data['city_id'])){
            $conditions['Experience.city_id'] = $request_data['city_id'];
        }
        if(!empty($request_data['city_name'])){
            $conditions['City.name'] = $request_data['city_name'];
        }
        if(!empty($request_data['country_id'])){
            $conditions['City.country_id'] = $request_data['country_id'];
        }
        if(!empty($request_data['user_name'])){
            //on separe le nom du prenom
            $names = explode(' ',$request_data['user_name']);
            if(count($names) > 1){
                $conditions['AND']['User.firstname LIKE'] = '%'.$names[0].'%';
                $conditions['AND']['User.lastname LIKE'] = '%'.$names[1].'%';
            }
            //si un seul mot a été entré (nom ou prenom)
            else{
                $conditions['OR'] = array('User.firstname LIKE' => '%'.$request_data['user_name'].'%',
                    'User.lastname LIKE' => '%'.$request_data['user_name'].'%');
            }
        }
        return $conditions;
    }
        
    protected function _upload_experienceNumber($city_id = null, $increment_by = null){
        
        if($city_id != null && $increment_by != null){
            $city = $this->Experience->City->find('first', array(
                'conditions' => array('City.id' => $city_id),
                'recursive' => 0
            ));
                
            //upload du nombre d'experience de la ville
            $count = $city['City']['experienceNumber'];
            $this->Experience->City->id = $city_id;
                
            if($this->Experience->City->saveField('experienceNumber',$count+$increment_by)){
                //upload du nombre d'experiences du pays de la ville
                $count = $city['Country']['experienceNumber'];
                $this->Experience->City->Country->id = $city['Country']['id'];
                return $this->Experience->City->Country->saveField('experienceNumber',$count+$increment_by);
            }
        }
        return false;
    }

    // private function __send_remindExperience_email($user) {
        
    //     $experiences = $this->Experience->find('all');
    //     foreach($experiences as $experience) {
    //         if($experienceEnded) {
    //             App::uses('CakeEmail','Network/Email');
    //             $email = new CakeEmail('default');
    //             $email->to($user['User']['email'])
    //                     ->subject("Polytech Abroad | Alors, c'était comment ?")
    //                     ->emailFormat('html')
    //                     ->template('experience_ended')
    //                     ->send();
    //         }
    //     }
    // }
        
    /**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
                $this->Paginator->settings = $this->paginate;
                $this->Paginator->settings['order'] = array('created' => 'DESC');
		$this->Experience->recursive = 0;
		$this->set('experiences', $this->Paginator->paginate());
	}
            
/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Experience->exists($id)) {
			throw new NotFoundException(__('Invalid experience'));
		}
		$options = array('conditions' => array('Experience.' . $this->Experience->primaryKey => $id));
		$this->set('experience', $this->Experience->find('first', $options));
	}
            
/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->Experience->exists($id)) {
			throw new NotFoundException(__('Invalid experience'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->Experience->save($this->request->data)) {
				$this->Session->setFlash(__('The experience has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The experience could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Experience.' . $this->Experience->primaryKey => $id));
			$this->request->data = $this->Experience->find('first', $options);
		}
		$cities = $this->Experience->City->find('list');
		$motives = $this->Experience->Motive->find('list');
		$users = $this->Experience->User->find('list');
		$this->set(compact('cities', 'motives', 'users'));
	}
            
/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Experience->id = $id;
                $experience = $this->Experience->findById($id);
		if (!$this->Experience->exists()) {
			throw new NotFoundException(__('Invalid experience'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Experience->delete() && $this->_upload_experienceNumber($experience['Experience']['city_id'],-1)) {
			$this->Session->setFlash(__('The experience has been deleted.'));
		} else {
			$this->Session->setFlash(__('The experience could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
            
}
?>