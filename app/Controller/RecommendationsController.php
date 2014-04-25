<?php
App::uses('AppController', 'Controller');
    
class RecommendationsController extends AppController {
        
    public function beforeFilter() {
        parent::beforeFilter();
    }
        
    public function add_recommendation(){
        
        $this->request->onlyAllow('ajax');
                
        //puis ajout de la recommandation
        $this->Recommendation->create();
        $recommendation = array();
        $recommendation['Recommendation']['content'] = $this->request->data['content'];
        $recommendation['Recommendation']['experience_id'] = $this->request->data['experience_id'];
        $recommendation['Recommendation']['recommendationtype_id'] = $this->request->data['recommendationtype_id'];

        if($this->Recommendation->save($recommendation)){
            return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>0)),'status'=>200));
        }

        return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>"Erreur lors de l'ajout")),'status'=>500));
        
    }
            
}
?>