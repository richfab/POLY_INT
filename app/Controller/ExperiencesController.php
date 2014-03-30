<?php
class ExperiencesController extends AppController {

    public $helpers = array('Html', 'Form');
    
    public $components = array("RequestHandler");
    
    /* Set pagination options */
    public $paginate = array(
            'limit' => 20,
            'order' => array('dateEnd' => 'DESC')
    );

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('get_map_init','get_map')); //ce que tout le monde a le droit de voir
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
        
        //on inclut le script google maps pour l'autocomplete des lieux et celui de la distance
    	$this->set('jsIncludes',array('http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places','places_autocomplete','bootstrap-rating-input'));
        
        $user_id = $this->Auth->user('id');
        $this->request->data['Experience']['user_id'] = $user_id;
        
        if ($this->request->is('post') || $this->request->is('put')) {
            
            //si c'est une modification d'experience, on decremente l'ancienne ville
            if($experience_id != null){
                $experience = $this->Experience->findById($experience_id);
                $this->upload_experienceNumber($experience['Experience']['city_id'],-1);
            }
            
            //on renseigne l'id de la ville de l'experience
            $city_id = $this->createCityAndCountryIfNeeded($this->request->data, $this->request->data['City']);
            $this->request->data['Experience']['city_id'] = $city_id;
            
            //si c'est une modification d'experience on renseigne l'id
            if($experience_id != null){
                $this->request->data['Experience']['id'] = $experience_id;
            }
        	
            $this->Experience->create();
            
            if ($this->Experience->save($this->request->data) && $this->upload_experienceNumber($city_id,1)) {
                
                $experience_id = $this->Experience->id;
                $experience = $this->Experience->findById($experience_id);
                
                return $this->redirect(array('controller'=>'users', 'action' => 'profile'));             
            }
            $this->Session->setFlash("Erreur lors de l'enregistrement");
        }
        else{
            $this->request->data = $this->Experience->find('first', array('conditions' => array('Experience.id' => $experience_id), 'recursive' => 2));
        }
    }
    
    public function index(){
        //on inclut le script pour la recuperation des experiences
    	$this->set('jsIncludes',array('get_experiences'));
    }
    
    //cette fonction retourne l'id de la ville, qu'elle ait été créée ou non
    public function createCityAndCountryIfNeeded($city_input = null, $country_input = null){
        
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
        if ($this->Experience->delete() && $this->upload_experienceNumber($experience['Experience']['city_id'],-1)) {
            $this->Session->setFlash("L'expérience a bien été supprimée");
            return $this->redirect($this->referer());
        }
        $this->Session->setFlash("L'expérience n'a pas pu être supprimée");
        return $this->redirect($this->referer());
    }
    
    public function get_map_init(){
//        $this->request->onlyAllow('ajax');
        $this->set('countries', $this->Experience->City->Country->find('all'));
    }
    
    public function get_map(){
        
        $this->request->onlyAllow('ajax');
        
        //on transforme l'objet de parametres en conditions
        if(!empty($this->request->data['motive_id'])){
            $conditions['Experience.motive_id'] = $this->request->data['motive_id'];
        }
        if(!empty($this->request->data['department_id'])){
            $conditions['User.department_id'] = $this->request->data['department_id'];
        }
        if(!empty($this->request->data['school_id'])){
            $conditions['User.school_id'] = $this->request->data['school_id'];
        }
        if(!empty($this->request->data['dateMin'])){
            $conditions['Experience.dateEnd >='] = $this->request->data['dateMin'];
        }
        if(!empty($this->request->data['dateMax'])){
            $conditions['Experience.dateStart <='] = $this->request->data['dateMax'];
        }
        
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
   
    public function upload_experienceNumber($city_id = null, $increment_by = null){
        
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