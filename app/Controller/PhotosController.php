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
    }
        
    /**
    * This method allows user to add a fb photo
    *
    * @return void
    */
    public function add_fb_photo(){
        
        $this->request->onlyAllow('ajax');
        
        //data retrieved
        $fb_id = $this->request->data['fb_id'];
        $fbalbum_id = $this->request->data['fbalbum_id'];
        $experience_id = $this->request->data['experience_id'];
        $url = $this->request->data['source'];
        
        
        //checks that experience number belongs to user
        if(count($this->Photo->Experience->findByIdAndUserId($experience_id,$this->Auth->user('id'))) == 0){
            return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>"L'experience n'appartient pas a l'utilisateur connecté")),'status'=>500));
        }
            
        //checks wether photo is already imported
        if(count($this->Photo->findByFbIdAndExperienceId($fb_id, $experience_id)) > 0 ){
            return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>'Photo deja importée')),'status'=>200));
        }
        
        $source =  IMAGES_URL . 'fb_photos/' . $this->Auth->user('id').'-'.md5(rand() . uniqid() . time()). '.jpg';
            
        //checks upload success
        if(!file_put_contents($source, file_get_contents($url))){
            return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>"Erreur lors de l'enregistrement de l'image")),'status'=>500));
        }
           
        //creates recommandation from request data
        $this->Photo->create();
        $photo = array();
        $photo['Photo']['fb_id'] = $fb_id;
        $photo['Photo']['source'] = '/' . $source;
        if(!empty($this->request->data['caption'])){
            $photo['Photo']['caption'] = $this->request->data['caption'];
        }
        $photo['Photo']['experience_id'] = $experience_id;
            
        //saves photo info and experience fbalbum id
        $this->Photo->Experience->id = $experience_id;
        if($this->Photo->save($photo) && $this->Photo->Experience->saveField('fbalbum_id',$fbalbum_id)){
            return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>0)),'status'=>200));
        }
        else{
            return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>"Erreur lors de l'enregistrement")),'status'=>500));
        }
            
    }
        
    /**
    * This method gets the data and renders the photo gallery
    *
    * @return void
    */
    public function get_photo_gallery($experience_id){
        
        $this->request->onlyAllow('ajax');
            
        $conditions = array();
        $conditions['Photo.experience_id'] = $experience_id;
            
        $photos = $this->Photo->find('all', array(
                    'conditions' => $conditions,
                    'limit' => 25,
                    'order' => array('Photo.created' => 'ASC')));
                        
        //si aucune photo
        if(count($photos)==0){
//            return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>'Aucune photo')),'status'=>500));
        }
            
        $this->set('photos', $photos);
            
        $this->layout = false;
        $this->render('/Elements/photo_gallery');
    }
        
}
?>