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
        $this->Auth->allow(array('search','get_recommendations')); //actions that anyone is allowed to call
    }
        
    /**
    * This method allows user to add a recommendation
    *
    * @return void
    */
    public function add_recommendation(){
        
        $this->request->onlyAllow('ajax');
                
        //creates recommandation from request data
        $this->Recommendation->create();
        $recommendation = array();
        $recommendation['Recommendation']['content'] = $this->request->data['content'];
        $recommendation['Recommendation']['experience_id'] = $this->request->data['experience_id'];
        $recommendation['Recommendation']['recommendationtype_id'] = $this->request->data['recommendationtype_id'];
        
        //saves recommendation
        if($this->Recommendation->save($recommendation)){
            return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>0)),'status'=>200));
        }
        else{
            return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>"Erreur lors de l'enregistrement")),'status'=>500));
        }
        
    }
    
    /**
    * This method allows user to delete a recommendation
    *
    * @param string $id
    * @return void
    */
    public function delete($id = null) {
        $this->Recommendation->id = $id;
        if (!$this->Recommendation->exists()) {
                throw new NotFoundException(__("Ce bon plan n'éxiste plus"));
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Recommendation->delete()) {
                $this->Session->setFlash(__("Le bon plan a bien été supprimé"), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-success'
                ));
        } else {
                $this->Session->setFlash(__("Le bon plan n'a pas pu être supprimé"), 'alert', array(
                    'plugin' => 'BoostCake',
                    'class' => 'alert-danger'
                ));
        }
        return $this->redirect($this->referer());
    }
    
    /**
    * This method allows user to search recommendations
    *
    * @return void
    */
    public function search(){
        //includes google maps script for place autocomplete and to get recommendation
    	$this->set('jsIncludes',array('http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=fr&libraries=places','places_autocomplete','get_recommendations','readmore','logo_fly'));
        
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