<?php
App::uses('AppController', 'Controller');
    
class RecommendationsController extends AppController {
    
    //pour l'extension json
    public $components = array("RequestHandler");
        
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('search','get_recommendations')); //ce que tout le monde a le droit de faire
    }
        
    public function add_recommendation(){
        
        $this->request->onlyAllow('ajax');
                
        //ajout de la recommandation
        $this->Recommendation->create();
        $recommendation = array();
        $recommendation['Recommendation']['content'] = $this->request->data['content'];
        $recommendation['Recommendation']['experience_id'] = $this->request->data['experience_id'];
        $recommendation['Recommendation']['recommendationtype_id'] = $this->request->data['recommendationtype_id'];
        
        //on ajoute la recommandation
        if($this->Recommendation->save($recommendation)){
            return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>0)),'status'=>200));
        }
        else{
            return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>"Erreur lors de l'enregistrement")),'status'=>500));
        }
        
    }
    
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
    
    public function search(){
        //on inclut les scripts pour la recuperation des experiences et google maps pour l'autocomplete des lieux
    	$this->set('jsIncludes',array('http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=fr&libraries=places','places_autocomplete','get_recommendations'));
        
        //recupere les recommendationtypes
        $this->set('recommendationtypes',$this->Recommendation->Recommendationtype->find('all'));
    }
    
    public function get_recommendations(){
        
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
                
            $this->set('recommendations', $this->Recommendation->find('threaded', array(
                        'conditions' => $conditions,
                        'recursive' => 2,
                        'order' => array('Recommendation.modified' => 'DESC'),
                        'limit' => $result_limit,
                        'offset' => $offset)));
            
            //recupere les pays
            $this->set('countries',$this->Recommendation->Experience->City->Country->find('list'));
            //recupere les recommendationtypes (icones et names
            $this->set('recommendationtype_names',$this->Recommendation->Recommendationtype->find('list'));
            $this->set('recommendationtype_icons',$this->Recommendation->Recommendationtype->find('list',array(
                'fields' => array('Recommendationtype.icon')
            )));
        }
        
        $this->render('/Recommendations/get_recommendations');
    }
    
    //fonction qui transforme l'objet de parametres en conditions pour le find
    protected function _filters_to_conditions($request_data = null){
        
        $conditions = array();
            
        if(empty($request_data)){
            return $conditions;
        }
        if(!empty($request_data['recommendationtypes'])){
            $conditions['Recommendation.recommendationtype_id'] = $request_data['recommendationtypes'];
        }
        if(!empty($request_data['country_id'])){
            
            $city_ids = $this->Recommendation->Experience->City->find('list',array(
                'conditions' => array('City.country_id' => $request_data['country_id']),
                'fields' => array('City.id')
            ));
            
            $conditions['Experience.city_id'] = $city_ids;
        }
        if(!empty($request_data['city_name'])){
            
            $city_ids = $this->Recommendation->Experience->City->find('list',array(
                'conditions' => array('City.name' => $request_data['city_name'], 'City.country_id' => $request_data['country_id']),
                'fields' => array('City.id')
            ));
            
            $conditions['Experience.city_id'] = $city_ids;
        }
        return $conditions;
    }
            
}
?>