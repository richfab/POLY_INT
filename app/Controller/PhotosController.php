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
        $source = $this->request->data['source'];
        $source_s = $this->request->data['source_s'];
        $picture = $this->request->data['picture'];
        $fb_created = $this->request->data['fb_created'];
            
            
        //checks that experience number belongs to user
        if(count($this->Photo->Experience->findByIdAndUserId($experience_id,$this->Auth->user('id'))) == 0){
            return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>"L'experience n'appartient pas a l'utilisateur connecté")),'status'=>500));
        }
            
        //checks wether photo is already imported
        if($this->Photo->findByFbId($fb_id)){
            $existing_photo = $this->Photo->findByFbId($fb_id);
            $this->Photo->id = $existing_photo['Photo']['id'];
        }
        else{
            $this->Photo->create();
        }
            
        $photo = array();
        $photo['Photo']['fb_id'] = $fb_id;
        $photo['Photo']['source'] = $source;
        $photo['Photo']['source_s'] = $source_s;
        $photo['Photo']['picture'] = $picture;
        if(!empty($this->request->data['caption'])){
            $photo['Photo']['caption'] = $this->request->data['caption'];
        }
        $photo['Photo']['fb_created'] = $fb_created;
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
    public function get_photo_gallery($experience_id, $size, $limit = NULL){
        
        $this->request->onlyAllow('ajax');
            
        $conditions = array();
        $conditions['Photo.experience_id'] = $experience_id;
        
        if($size == 'M'){
            $fields = array('*', 'source AS image');
        }
        elseif ($size == 'S'){
            $fields = array('*', 'source_s AS image');
        }
        
        $order = array('Photo.fb_created' => 'DESC');
            
        $photos = $this->Photo->find('all', array(
                    'conditions' => $conditions,
                    'limit' => $limit,
                    'fields' => $fields,
                    'order' => $order));
                        
        //hidden photos are the photos after the limit which users want to see when browsing the album
        $hidden_photos = array();
            
        //if limit is not null, find the photos beyond the limit
        if($limit != NULL){
            $hidden_photos = $this->Photo->find('all', array(
                        'conditions' => $conditions,
                        'offset' => $limit,
                        'limit' => 1000,
                        'fields' => $fields,
                        'order' => $order));
        }
            
        $this->set(array('photos' => $photos, 'hidden_photos' => $hidden_photos));
            
        $this->layout = false;
        $this->render('/Elements/photo_gallery');
    }
        
    /**
    * This method gets the fb_album import buttons
    *
    * @return void
    */
    public function get_fbalbum_import_button($experience_id){
        
        $this->request->onlyAllow('ajax');
            
        $experience = $this->Photo->Experience->findById($experience_id);
            
        $this->set('experience', $experience);
            
        $this->layout = false;
        $this->render('/Elements/fbalbum_import_button');
    }
        
    /**
    * This method allows user to delete a photo
    * 
    */
    public function delete() {
        
        $this->request->onlyAllow('ajax');
            
        $photo_id = $this->request->data['photo_id'];
            
        $photo = $this->Photo->findById($photo_id);
        $experience = $this->Photo->Experience->findById($photo['Photo']['experience_id']);
            
        //checks that photo belongs to current user
        $user_id = $this->Auth->user('id');
            
        if($experience['Experience']['user_id'] != $user_id){
            return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>"Photo does not belong to current user")),'status'=>500));
        }
            
        if ($this->_delete($photo_id)){
            return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>0)),'status'=>200));
        }
            
        return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>"Error during deletion")),'status'=>500));
    }
        
    /**
    * This protected method deletes the photo
    * 
    */
   protected function _delete($photo_id) {
       
        $this->Photo->id = $photo_id;
            
        if ($this->Photo->delete()){
            return true;
        }
            
        return false;
            
    }
        
    /**
    * This method allows user to delete a photo album
    * 
    */
    public function delete_album() {
        
        $this->request->onlyAllow('ajax');
            
        $experience_id = $this->request->data['experience_id'];
            
        $experience = $this->Photo->Experience->findById($experience_id);
            
        //checks that experience belongs to current user
        $user_id = $this->Auth->user('id');
        if($experience['Experience']['user_id'] != $user_id){
            return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>"Album does not belong to current user")),'status'=>500));
        }
            
        $photos = $this->Photo->findAllByExperienceId($experience_id);
            
        $photo_delete_success = true;
            
        foreach ($photos as $photo) {
            if(!$this->_delete($photo['Photo']['id'])){
                $photo_delete_success = false;
            }
        }
            
        if($photo_delete_success){
            
            $this->Photo->Experience->id = $experience_id;
                
            //saves fbalbum_id in experience
            if($this->Photo->Experience->saveField('fbalbum_id',NULL)){
                return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>0)),'status'=>200));
            }
        }
        return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>"Error during deletion")),'status'=>500));
            
    }
        
}
?>