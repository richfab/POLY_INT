<?php
class ExperiencesController extends AppController {
    
    //pour l'extension json
    public $components = array("RequestHandler");
    
    /* Set pagination options */
    public $paginate = array(
            'limit' => 20,
            'order' => array('dateEnd' => 'DESC')
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
            $this->request->data = $this->Experience->find('first', array('conditions' => array('Experience.id' => $experience_id), 'recursive' => 2));
        }
    }
    
    public function explore(){
        //on inclut les scripts pour la recuperation des experiences
    	$this->set('jsIncludes',array('get_experiences','jvector','jquery-jvectormap.min','jquery-jvectormap-world-mill-en','modernizr.custom.63321','jquery.dropdown'));
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
        if ($this->Experience->delete() && $this->_upload_experienceNumber($experience['Experience']['city_id'],-1)) {
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
    	$this->set('jsIncludes',array('http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=fr&libraries=places','places_autocomplete','get_experiences','modernizr.custom.63321','jquery.dropdown'));
        //on inclut les style pour la carte
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
            if(!empty($this->request->data['result_limit'])){
                $result_limit = $this->request->data['result_limit'];
            }
            else{
                $result_limit = 100000;
            }
            
            $this->set('result_limit',$result_limit);
            
            $this->set('experiences', $this->Experience->find('all', array(
                        'conditions' => $conditions,
                        'recursive' => 2,
                        'limit' => $result_limit,
                        'fields' => array('*','DATEDIFF(Experience.dateEnd, Experience.dateStart)/30 monthDiff'))));
        }
        $this->render('/Experiences/'.$this->request->data['view_to_render']);
    }
    
    //fonction qui transforme l'objet de parametres en conditions pour le find
    protected function _filters_to_conditions($request_data = null){
        
        $conditions = array();
        
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
        //future
        if(!empty($request_data['date_min'])){
            $conditions['Experience.dateEnd >='] = $request_data['date_min'];
        }
        //passee
        if(!empty($request_data['date_max'])){
            $conditions['Experience.dateStart <='] = $request_data['date_max'];
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
            $conditions['OR'] = array('User.firstname LIKE' => '%'.$request_data['user_name'].'%',
                'User.lastname LIKE' => '%'.$request_data['user_name'].'%');
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
    
}
?>