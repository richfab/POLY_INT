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
        $this->Auth->allow(array()); //ce que tout le monde a le droit de voir
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
    	$this->set('jsIncludes',array('http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places','places_autocomplete'));
        
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
                
                //on teste si la date de fin de l'experience est inférieure à la date du jour
//                $today = date("Y-m-d H:i:s"); 
                
//                if($experience['Experience']['dateEnd'] <= $today){
                    return $this->redirect(array('controller'=>'experiences', 'action' => 'note', $experience['Experience']['id']));
//                }
//                else{
//                    return $this->redirect(array('controller'=>'experiences', 'action' => 'notify', $experience['Experience']['id']));
//                }
            }
            $this->Session->setFlash("Erreur lors de l'enregistrement");
        }
        else{
            $this->request->data = $this->Experience->find('first', array('conditions' => array('Experience.id' => $experience_id), 'recursive' => 2));
        }
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
            $country = $this->Experience->City->Country->find('first', array(
                'conditions' => array('Country.code' => $country_input['Country']['code'],
                    'Country.id' => $city['City']['country_id']),
                'recursive' => 0
            ));
        }
        
        //si la ville de ce pays n'existe pas dans la bdd
        if(empty($country)){
            
            //etape 2 : on teste si le pays existe deja dans la bdd
            $country = $this->Experience->City->Country->find('first', array(
                'conditions' => array('Country.code' => $country_input['Country']['code']),
                'recursive' => 0
            ));

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
    
    public function note($experience_id = null){
        App::uses('AuthComponent', 'Controller/Component');
        
        $user_id = $this->Auth->user('id');
        
        //on inclut le script nécessaire aux étoile de notation
        $this->set('jsIncludes',array('bootstrap-rating-input'));
        
        //on verifie que l'experience est bien celle de l'utilisateur connecté
        $experience = $this->Experience->find('first', array(
            'conditions' => array('Experience.user_id' => $user_id)
        ));
        
        //si l'utilisateur connecté est bien celui de l'experience
        if($experience['Experience']['user_id'] == $user_id){
            
            if ($this->request->is('post') || $this->request->is('put')) {
            
                $this->request->data['Experience']['user_id'] = $user_id;
                $this->request->data['Experience']['id'] = $experience_id;

                $this->Experience->create();

                if ($this->Experience->save($this->request->data)) {
                    return $this->redirect(array('controller'=>'experiences', 'action' => 'notify', $experience_id));
                }
                else{
                    $this->Session->setFlash("Erreur lors de l'enregistrement");
                }
            
            }
            else{
                $this->request->data = $this->Experience->findById($experience_id);
            }
            
        }
        else{
            //l'utilisateur essaie de modifier une experience qui n'est pas la sienne
            return $this->redirect(array('controller'=>'users', 'action' => 'login'));
        }
    } 
    
    public function notify($experience_id = null){
        App::uses('AuthComponent', 'Controller/Component');
        
        $user_id = $this->Auth->user('id');
        
        //on inclut le script nécessaire aux étoile de notation
        $this->set('jsIncludes',array('bootstrap-rating-input'));
        
        //on verifie que l'experience est bien celle de l'utilisateur connecté
        $experience = $this->Experience->find('first', array(
            'conditions' => array('Experience.user_id' => $user_id)
        ));
        
        if($experience['Experience']['user_id'] == $user_id){
            
            if ($this->request->is('post') || $this->request->is('put')) {
            
                $this->request->data['Experience']['user_id'] = $user_id;
                $this->request->data['Experience']['id'] = $experience_id;

                $this->Experience->create();

                if ($this->Experience->save($this->request->data)) {
                    return $this->redirect(array('controller'=>'users', 'action' => 'profile'));
                }
                else{
                    $this->Session->setFlash("Erreur lors de l'enregistrement");
                }
            
            }
            else{
                $this->request->data = $this->Experience->findById($experience_id);
            }
        }
        else{
            //l'utilisateur essaie de modifier une experience qui n'est pas la sienne
            return $this->redirect(array('controller'=>'users', 'action' => 'login'));
        }
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