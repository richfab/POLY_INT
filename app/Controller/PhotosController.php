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
class PhotosController extends AppController {
        
    /**
    * This method is called before the controller action. It is useful to define which actions are allowed publicly.
    *
    * @return void
    */
    public function beforeFilter() {
        parent::beforeFilter();
    }
        
    /**
    * This method allows user to add a fb photo
    *
    * @return void
    */
    public function add_fb_photo(){
        
        $this->request->onlyAllow('ajax');
        
        $url = $this->request->data['source'];
        $source =  IMAGES_URL . 'fb_photos/' . $this->Auth->user('id').'-'.md5(rand() . uniqid() . time()). '.jpg';
        
        if(!file_put_contents($source, file_get_contents($url))){
            return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>"Erreur lors de l'enregistrement de l'image")),'status'=>500));
        }
                
        //creates recommandation from request data
        $this->Photo->create();
        $photo = array();
        $photo['Photo']['fb_id'] = $this->request->data['fb_id'];
        $photo['Photo']['source'] = '/' . $source;
        if(!empty($this->request->data['caption'])){
            $photo['Photo']['caption'] = $this->request->data['caption'];
        }
        $photo['Photo']['experience_id'] = $this->request->data['experience_id'];
        
        //saves photo
        if($this->Photo->save($photo)){
            return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>0)),'status'=>200));
        }
        else{
            return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>"Erreur lors de l'enregistrement")),'status'=>500));
        }
        
    }
            
}
?>