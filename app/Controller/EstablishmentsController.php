<?php
/**
 * Establishments Controller
 *
 * @property Establishments $Establishments
 * @property PaginatorComponent $Paginator
 */
App::uses('AppController', 'Controller');

/**
 * Establishments Controller
 *
 * This class defines all actions relative to Establishments
 *
 * @package		app.Controller
 */
class EstablishmentsController extends AppController {

    /**
    * Components to handle json requests
    *
    * @var array
    */
    public $components = array("RequestHandler");

    /**
    * This method is called before the controller action. It is useful to define which actions are allowed publicly.
    *
    * @return void
    */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('add_establishment_ajax')); //actions that anyone is allowed to call
    }

    /**
    * Fonction utile a l'ajout d'établissement de type google place
    * 
    * @return void
    */
    public function add_establishment_ajax(){

        $this->request->onlyAllow('ajax');

        $google_place_id = $this->request->data['google_place_id'];

        $establishment = $this->Establishment->find('first', array(
            'conditions' => array('google_place_id' => $google_place_id)
        ));

        //l'établissement existe déjà dans la base
        if(!empty($establishment)){
            $establishment_id = $establishment['Establishment']['id'];
        } else {
            // ajout de l'établissement
            $this->Establishment->create();
            $establishment = array();
            $establishment['Establishment']['name'] = $this->request->data['name'];
            $establishment['Establishment']['google_place_id'] = $this->request->data['google_place_id'];
            $establishment['Establishment']['city_id'] = $this->request->data['city_id'];

            if(!$this->Establishment->save($establishment)){
                return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>"Erreur lors de l'ajout")),'status'=>500));
            }
            
            $establishment_id = $this->Establishment->id;
        }

        return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>0, 'establishment_id' => $establishment_id)),'status'=>200));

    }

}
?>