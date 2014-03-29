<?php
class ExperiencesController extends AppController {

    public $helpers = array('Html', 'Form');
    
    public $components = array("RequestHandler");
    
    /* Set pagination options */
    public $paginate = array(
            'limit' => 20,
            'order' => array('created' => 'DESC')
    );

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(); //ce que tout le monde a le droit de voir
    }
    
    public function add(){
    	App::uses('AuthComponent', 'Controller/Component');
        
        //selectionne les motifs par ordre alphabetique
        $this->set('motives', $this->Experience->Motive->find('list', array(
                        'order' => array('Motive.name' => 'ASC'))));
        
        //on inclut le script google maps pour l'autocomplete des lieux et celui de la distance
    	$this->set('jsIncludes',array('http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places','places_autocomplete'));
        
        $user_id = $this->Auth->user('id');
        $this->request->data['Experience']['user_id'] = $user_id;
        
        if ($this->request->is('post')) {
            
            //etape 1 : on teste si la ville existe deja dans la base
            $city = $this->Experience->City->find('first', array(
                'conditions' => array('City.name' => $this->request->data['City']['name']),
                'recursive' => 0
            ));
            //si la ville n'existe pas dans la bdd
            if(empty($city)){
                //etape 2 : on teste si le pays existe deja dans la bdd
                $country = $this->Experience->City->Country->find('first', array(
                    'conditions' => array('Country.name' => $this->request->data['Country']['name']),
                    'recursive' => 0
                ));
                
                //si le pays n'existe pas dans la bdd, on le creer
                if(empty($country)){
                    $country = $this->Experience->City->Country->save($this->request->data);
                }
                
                //puis on creer la ville
                $this->request->data['City']['country_id'] = $country['Country']['id'];
                $city = $this->Experience->City->save($this->request->data);
            }
            
            //on renseigne l'id de la ville de l'experience
            $this->request->data['Experience']['city_id'] = $city['City']['id'];
        	
            $this->Experience->create();
            
            if ($this->Experience->save($this->request->data) && $this->upload_experienceNumber($city['City']['id'],1)) {
                
                $experience = $this->Experience->find('first', array(
                    'conditions' => array('Experience.user_id' => $user_id),
                    'order' => array(
                        'Experience.created' => 'DESC'
                    )
                ));
                
                //on teste si la date de fin de l'experience est inférieure à la date du jour
                $today = date("Y-m-d H:i:s"); 
                if($experience['Experience']['dateEnd'] < $today){
                    return $this->redirect(array('controller'=>'experiences', 'action' => 'note', $experience['Experience']['id']));
                }
                else{
                    return $this->redirect(array('controller'=>'experiences', 'action' => 'notify', $experience['Experience']['id']));
                }
            }
            $this->Session->setFlash("Erreur lors de l'enregistrement");
        }
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
        
        if($experience['Experience']['user_id'] == $user_id){
            
            if ($this->request->is('post')) {
            
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
            
            if ($this->request->is('post')) {
            
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
        }
        else{
            //l'utilisateur essaie de modifier une experience qui n'est pas la sienne
            return $this->redirect(array('controller'=>'users', 'action' => 'login'));
        }
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