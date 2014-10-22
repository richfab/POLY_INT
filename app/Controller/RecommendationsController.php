<?php
/**
 * Recommendations Controller
 *
 * @property Recommendation $Recommendation
 * @property PaginatorComponent $Paginator
 */
App::uses('AppController', 'Controller');

/**
 * Recommendations Controller
 *
 * This class defines all actions relative to Recommendations
 *
 * @package		app.Controller
 */
class RecommendationsController extends AppController {
    
    /**
    * Components
    *
    * @var array
    */
    public $components = array('RequestHandler','Paginator', 'Session');

    /**
    * Pagination options
    *
    * @var array
    */
    public $paginate = array(
            'limit' => 20,
            'order' => array('created' => 'DESC')
    );
        
    /**
    * This method is called before the controller action. It is useful to define which actions are allowed publicly.
    *
    * @return void
    */
    public function beforeFilter() {
        parent::beforeFilter();
		//actions that anyone is allowed to call
        $this->Auth->allow(array('search','get_recommendations')); 
    }
	
	public function view($id) {
        $recommendation = $this->Recommendation->findById($id);
        $this->set(array(
            'recommendation' => $recommendation,
            '_serialize' => array('recommendation')
        ));
    }
	
	public function delete($id) {
		if(!$this->recommendation_belongs_to_user($id)){
			return;
		}
        if ($this->Recommendation->delete($id)) {
            $message = 'Deleted';
        } else {
            $message = 'Error';
        }
        $this->set(array(
            'message' => $message,
            '_serialize' => array('message')
        ));
    }
	
	public function add(){
		if ($this->Recommendation->save($this->request->data)) {
            $message = 'Saved';
        } else {
            $message = 'Error';
        }
        $this->set(array(
            'message' => $message,
            '_serialize' => array('message')
        ));
	}
	
	public function edit($id){
		if(!$this->recommendation_belongs_to_user($id)){
			return;
		}
		$this->Recommendation->id = $id;
		if ($this->Recommendation->save($this->request->data)) {
            $message = 'Saved';
        } else {
            $message = 'Error';
        }
        $this->set(array(
            'message' => $message,
            '_serialize' => array('message')
        ));
	}
	
    /**
    * This method allows to determine wether a recommendation belongs to logged in user or not
    *
	* @param string $id (recommendation_id)
    * @return boolean
    */
	public function recommendation_belongs_to_user($id){
		$recommendation = $this->Recommendation->findById($id);
        App::import('Controller', 'Experiences');
        $experiencesController = new ExperiencesController;
		$experience = $experiencesController->Experience->findById($recommendation['Experience']['id']);
		return $experience['Experience']['user_id'] == $this->Auth->user('id');
	}
    
    /**
    * This method allows user to search recommendations
    *
    * @return void
    */
    public function search(){
        //includes google maps script for place autocomplete and to get recommendation
    	$this->set('jsIncludes',array('http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=fr&libraries=places','places_autocomplete','masonry.pkgd.min','get_recommendations','logo_fly'));
        
        //sets recommendation types
        $this->set('recommendationtypes',$this->Recommendation->Recommendationtype->find('all'));
    }
    
    /**
    * This method gets recommendations
    *
    * @return void
    */
    public function get_recommendations(){
        
        $this->request->onlyAllow('ajax');
        App::uses('AuthComponent', 'Controller/Component');
            
         //gets recommendations only of user is logged in
        if($this->Auth->user('id')){
            //converts filter parameters into conditions
            $conditions = $this->_filters_to_conditions($this->request->data);
            
            //defines number of results and offset
            $offset = $this->request->data['offset'];
            $result_limit = 20;
            
            $this->set('result_limit',$result_limit);
            $this->set('offset',$offset);
                
            $this->set('recommendations', $this->Recommendation->find('threaded', array(
                        'conditions' => $conditions,
                        'recursive' => 2,
                        'order' => array('Recommendation.modified' => 'DESC'),
                        'limit' => $result_limit,
                        'offset' => $offset)));
            
            //sets next offset
            $this->set(array('next_offset' => $offset+$result_limit));
            
            //sets countries
            $this->set('countries',$this->Recommendation->Experience->City->Country->find('list'));
            //sets recommendationtypes (icons et names)
            $this->set('recommendationtype_names',$this->Recommendation->Recommendationtype->find('list'));
            $this->set('recommendationtype_icons',$this->Recommendation->Recommendationtype->find('list',array(
                'fields' => array('Recommendationtype.icon')
            )));
        }
        
        $this->render('/Recommendations/get_recommendations');
    }
    
    /**
    * This protected method converts filter parameters into conditions
    * 
    * @param string $request_data
    * @return array
    */
    protected function _filters_to_conditions($request_data = null){
        
        $conditions = array();
            
        if(empty($request_data)){
            return $conditions;
        }
        //if recommendationtype was selected
        if(!empty($request_data['recommendationtypes'])){
            $conditions['Recommendation.recommendationtype_id'] = $request_data['recommendationtypes'];
        }
        //if country was selected in autocomplete
        if(!empty($request_data['country_id'])){
            
            $city_ids = $this->Recommendation->Experience->City->find('list',array(
                'conditions' => array('City.country_id' => $request_data['country_id']),
                'fields' => array('City.id')
            ));
            
            $conditions['Experience.city_id'] = $city_ids;
        }
        //if city was selected in autocomplete
        if(!empty($request_data['city_name'])){
            
            $city_ids = $this->Recommendation->Experience->City->find('list',array(
                'conditions' => array('City.name' => $request_data['city_name'], 'City.country_id' => $request_data['country_id']),
                'fields' => array('City.id')
            ));
            
            $conditions['Experience.city_id'] = $city_ids;
        }
        return $conditions;
    }
    
    /**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
            $this->Paginator->settings = $this->paginate;
            $this->Recommendation->recursive = 0;
            $this->set('recommendations', $this->Paginator->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->Recommendation->exists($id)) {
			throw new NotFoundException(__('Invalid recommendation'));
		}
		$options = array('conditions' => array('Recommendation.' . $this->Recommendation->primaryKey => $id));
		$this->set('recommendation', $this->Recommendation->find('first', $options));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->Recommendation->id = $id;
		if (!$this->Recommendation->exists()) {
			throw new NotFoundException(__('Invalid recommendation'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Recommendation->delete()) {
			$this->Session->setFlash(__('The recommendation has been deleted.'));
		} else {
			$this->Session->setFlash(__('The recommendation could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
            
}
?>