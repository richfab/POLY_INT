<?php
/**
 * Experiences Controller
 *
 * @property Experience $Experience
 * @property PaginatorComponent $Paginator
 */
App::uses('AppController', 'Controller');

/**
 * Experiences Controller
 *
 * This class defines all actions relative to Experiences
 *
 * @package		app.Controller
 */
class ExperiencesController extends AppController {

    /**
    * Components to handle json requests
    *
    * @var array
    */
    public $components = array("RequestHandler");

    /**
    * Pagination options
    *
    * @var array
    */
    public $paginate = array(
        'limit' => 20,
        'order' => array('dateEnd' => 'DESC'),
        'conditions' => array('User.active'=>'1')
    );

    /**
    * Helper to handle image resize
    *
    * @var array
    */
    public $helpers = array('Image.Image');

    /**
    * This method is called before the controller action. It is useful to define which actions are allowed publicly.
    *
    * @return void
    */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('explore','get_map_init','get_map','get_experiences','search')); //actions that anyone is allowed to call
    }

    /**
    * This method allows user to edit or add an experience
    * 
    * @param string $experience_id
    * @throws NotFoundException 
    * @return void
    */
    public function info($experience_id = null){

        if($experience_id != null){
            $this->Experience->id = $experience_id;
            if (!$this->Experience->exists()) {
                throw new NotFoundException(__("Cette experience n'existe plus"));
            }
        }

        //sets motives by alphabetical order
        $this->set('motives', $this->Experience->Motive->find('list', array(
            'order' => array('Motive.name' => 'ASC'))));

        //set notification types by alphabetical order
        $this->set('typenotifications', $this->Experience->Typenotification->find('list', array(
            'order' => array('Typenotification.id' => 'DESC'))));

        //includes google maps script for place autocomplete
        $this->set('jsIncludes',array('http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=fr&libraries=places','places_autocomplete'));

        $user_id = $this->Auth->user('id');
        $this->request->data['Experience']['user_id'] = $user_id;

        if ($this->request->is('post') || $this->request->is('put')) {

            $dateStart = $this->data['Experience']['dateStart']['year'].'-'.$this->data['Experience']['dateStart']['month'].'-'.$this->data['Experience']['dateStart']['day'];
            $dateEnd = $this->data['Experience']['dateEnd']['year'].'-'.$this->data['Experience']['dateEnd']['month'].'-'.$this->data['Experience']['dateEnd']['day'];

            //checks that dateEnd is after dateStart
            if ($dateEnd < $dateStart) {
                $this->Session->setFlash(__("La date de fin doit être après la date de début"), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));              
                return;
            }

            //decrements experience number for this place if user is editing experience
            if($experience_id != null){
                $experience = $this->Experience->findById($experience_id);
                $this->_upload_experienceNumber($experience['Experience']['city_id'],-1);
            }

            //sets experience city id
            $city_id = $this->_createCityAndCountryIfNeeded($this->request->data, $this->request->data['City']);
            $this->request->data['Experience']['city_id'] = $city_id;

            //sets experience id if user is editing experience
            if($experience_id != null){
                $this->request->data['Experience']['id'] = $experience_id;
            }

            $this->Experience->create();

            //saves experience and increments experience number for this place
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
            //sets countries
            $this->set('countries',$this->Experience->City->Country->find('list'));
        }
    }

    /**
    * This method allows user to explore experience map
    * 
    * @return void
    */
    public function explore(){
        //includes scripts to get experiences
        $this->set('jsIncludes',array('get_experiences','logo_fly','jvector','jquery-jvectormap.min','jquery-jvectormap-world-mill-en','jquery.dropdown','cookies'));
        //includes styles for map and filters
        $this->set('cssIncludes',array('jvectormap','map'));

        //sets motives, schools and departments by alphbetical order
        $this->__set_motives_schools_and_departments();
    }

    /**
    * This method allows user to search experiences
    * 
    * @return void
    */
    public function search(){
        //includes google maps script for place autocomplete and to get experiences
        $this->set('jsIncludes',array('http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=fr&libraries=places','places_autocomplete','get_experiences','logo_fly','jquery.dropdown','cookies'));

        //sets motives, schools and departments by alphbetical order
        $this->__set_motives_schools_and_departments();
    }

    /**
    * This private method sets motives, schools and departments by alphbetical order to populate select inputs
    * 
    * @return void
    */
    private function __set_motives_schools_and_departments() {
        //sets motives, schools and departments by alphabetical order
        $this->set('motives', $this->Experience->Motive->find('list', array(
            'order' => array('Motive.name' => 'ASC'))));
        //sets schools by alphabetical order
        $this->set('schools', $this->Experience->User->School->find('list', array(
            'order' => array('School.name' => 'ASC'))));
        //sets departements by alphabetical order
        $this->set('departments', $this->Experience->User->Department->find('list', array(
            'order' => array('Department.name' => 'ASC'))));
    }

    /**
    * This protected method returns city id after creating city and/or country if necessary
    * 
    * @param array $city_input
    * @param array $country_input
    * @return string city id
    */
    protected function _createCityAndCountryIfNeeded($city_input = null, $country_input = null){

        //step 1 : test whether the city already exists for this country
        $city = $this->Experience->City->find('first', array(
            'conditions' => array('City.name' => $city_input['City']['name'], 'City.country_id' => $country_input['Country']['id']),
            'recursive' => 0
        ));
        //if a city was found, set the country
        if(!empty($city)){
            $country = $this->Experience->City->Country->findById($city['City']['country_id']);
        }

        //if the city does not exist
        if(empty($country)){

            //step 2 : test whether the country already exists
            $country = $this->Experience->City->Country->findById($country_input['Country']['id']);

            //if country does not exist, it is created
            if(empty($country)){
                $this->request->data['Country'] = $country_input['Country'];
                $this->Experience->City->Country->create();
                $country = $this->Experience->City->Country->save($this->request->data);
            }

            //finally, create the city
            $this->request->data['City'] = $city_input['City'];
            $this->request->data['City']['country_id'] = $country['Country']['id'];
            $this->Experience->City->create();
            $city = $this->Experience->City->save($this->request->data);
        }
        return $city['City']['id'];
    }

    /**
    * This method allows user to delete an experience
    * 
    * @param string $experience_id
    * @throws NotFoundException
    */
    public function delete($experience_id = null) {
        $this->request->onlyAllow('post');

        $experience = $this->Experience->findById($experience_id);

        //checks that experience belongs to current user
        $user_id = $this->Auth->user('id');
        if($experience['Experience']['user_id'] != $user_id){
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

    /**
    * This method deletes an experience and decrements number of experiences in this place
    * 
    * @param string $experience_id
    * @return boolean
    */
    public function delete_experience($experience_id) {

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

    /**
    * This method gets experiences to color the map for map initialization
    * 
    * @return void
    */
    public function get_map_init(){
        //        $this->request->onlyAllow('ajax');
        $this->set('countries', $this->Experience->City->Country->find('all',array(
            'conditions' => array('Country.experienceNumber >' => 0)
        )));
    }

    /**
    * This method gets experiences to color the map
    * 
    * @return void
    */
    public function get_map(){

        $this->request->onlyAllow('ajax');

        //converts filter parameters into conditions
        $conditions = $this->_filters_to_conditions($this->request->data);

        //sets number of experience per city
        $this->set('cities', $this->Experience->find('all', array(
            'conditions' => $conditions,
            'fields' => array('City.id','City.name','City.lat','City.lon','(COUNT(*)) as experienceNumber'),
            'group' => 'Experience.city_id')));

        //sets number of experience per country
        $this->set('countries', $this->Experience->find('all', array(
            'conditions' => $conditions,
            'fields' => array('City.country_id','(COUNT(*)) as experienceNumber'),
            'group' => 'City.country_id')));
    }

    /**
    * This method gets experiences
    * 
    * @return void
    */
    public function get_experiences(){

        $this->request->onlyAllow('ajax');
        App::uses('AuthComponent', 'Controller/Component');

        //gets experiences only of user is logged in
        if($this->Auth->user('id')){
            //converts filter parameters into conditions
            $conditions = $this->_filters_to_conditions($this->request->data);

            //defines number of results and offset
            $offset = $this->request->data['offset'];
            $result_limit = 20;

            $this->set('result_limit',$result_limit);
            $this->set('offset',$offset);

            $this->set('experiences', $this->Experience->find('all', array(
                'conditions' => $conditions,
                'recursive' => 1,
                //order is given by dateSort (calculated in "fields")
                'order' => 'dateSort ASC',
                'limit' => $result_limit,
                'offset' => $offset,
                'fields' => array('*',
                                  //si l'experience est passée, on ajoute le nombre de jours entre la date de fin et aujourd'hui a une très grande date pour que ces expériences se retrouvent a la fin de la liste, sinon on prend simplement la date de debut
                                  'IF(DATEDIFF(Experience.dateEnd, NOW()) < 0,DATE_ADD("2200-01-01",INTERVAL ABS(DATEDIFF(Experience.dateEnd,NOW())) DAY),Experience.dateStart) AS dateSort',
                                  'DATEDIFF(Experience.dateEnd, Experience.dateStart)/30 monthDiff'))));

            //sets next offset
            $this->set(array('next_offset' => $offset+$result_limit));

            //sets countries
            $this->set('countries',$this->Experience->City->Country->find('list'));
            //sets departments
            $this->set('departments',$this->Experience->User->Department->find('list'));
            //sets schools
            $this->set('school_names',$this->Experience->User->School->find('list'));
            $this->set('school_colors',$this->Experience->User->School->find('list',array(
                'fields' => array('School.color'))));
        }
        //renders the view requested in view_to_render parameter
        $this->render('/Experiences/'.$this->request->data['view_to_render']);
    }

    /**
    * This method gets the establishment of all experiences
    * 
    * @return void
    */
    public function get_establishments(){

        $experiences = $this->Experience->find('all', array('fields' => array('id', 'establishment', 'City.id', 'City.lat', 'City.lon', 'City.name', 'City.country_id'),
                                                            'recursive' => 0,
                                                            'conditions' => array('establishment <>' => null)));
        $this->set('experiences', $experiences);

    }

    /**
    * This method gets the establishment of all experiences
    * 
    * @return void
    */
    public function get_establishments_google(){

        $this->set('jsIncludes',array('http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=fr&libraries=places'));

    }

    /**
    * Fonction utile a l'ajout d'experiences a partir d'une base de donnees comme celle de echarlemagne par exemple
    * 
    * @return void
    */
    public function echarlemagne(){
        //on inclut les scripts pour la recuperation des experiences et google maps pour l'autocomplete des lieux
        $this->set('jsIncludes',array('http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=fr&libraries=places','add_experiences'));
    }

    /**
    * Fonction utile a l'update de l'id d'établissement d'une expérience
    * 
    * @return void
    */
    public function save_experience_establishment_ajax(){

        $this->request->onlyAllow('ajax');

        $this->Experience->create();
        $this->Experience->id = $this->request->data['Experience']['id'];

        if($this->Experience->saveField('establishment_id', $this->request->data['Experience']['establishment_id'])){
            return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>0)),'status'=>200));
        }

        return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>"Erreur lors de l'ajout")),'status'=>500));

    }

    /**
    * Fonction utile a l'ajout d'experiences a partir d'une base de donnees comme celle de echarlemagne par exemple
    * 
    * @return void
    */
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
                $user['User']['active'] = "1";
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

    /**
    * This protected method converts filter parameters into conditions
    * 
    * @param string $request_data
    * @return array
    */
    protected function _filters_to_conditions($request_data = null){

        $conditions = array();
        //user has to have an active account
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
        //now
        if(!empty($request_data['date_min']) && !empty($request_data['date_max']) && ($request_data['date_min'] === $request_data['date_max'])){
            $conditions['AND'] = array('Experience.dateEnd >=' => $request_data['date_min'],
                                       'Experience.dateStart <=' => $request_data['date_max']);
        }
        else{
            //futur
            if(!empty($request_data['date_min'])){
                $conditions['Experience.dateStart >='] = $request_data['date_min'];
            }
            //past
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
            //extracts first and last names
            $names = explode(' ',$request_data['user_name']);
            if(count($names) > 1){
                $conditions['AND']['User.firstname LIKE'] = '%'.$names[0].'%';
                $conditions['AND']['User.lastname LIKE'] = '%'.$names[1].'%';
            }
            //if only last or first name was entered
            else{
                $conditions['OR'] = array('User.firstname LIKE' => '%'.$request_data['user_name'].'%',
                                          'User.lastname LIKE' => '%'.$request_data['user_name'].'%');
            }
        }
        return $conditions;
    }

    /**
    * This protected method uploads the number of experiences for a given city
    * 
    * @param string $city_id
    * @param int $increment_by
    * @return boolean
    */
    protected function _upload_experienceNumber($city_id = null, $increment_by = null){

        if($city_id != null && $increment_by != null){
            $city = $this->Experience->City->find('first', array(
                'conditions' => array('City.id' => $city_id),
                'recursive' => 0
            ));

            //uploads experience number of the city
            $count = $city['City']['experienceNumber'];
            $this->Experience->City->id = $city_id;

            if($this->Experience->City->saveField('experienceNumber',$count+$increment_by)){
                //uploads experience number of the country in which the city is located
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