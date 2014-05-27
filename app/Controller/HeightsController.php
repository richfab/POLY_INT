<?php
App::uses('AppController', 'Controller');
    
class HeightsController extends AppController {
    
    //pour l'extension json
    public $components = array("RequestHandler");
        
    /* Set pagination options */
    public $paginate = array(
            'limit' => 20,
            'order' => array('dateEnd' => 'DESC'),
            'conditions' => array('User.active'=>'1')
    );
        
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('contest','get_heights','get_heights_gallery')); //ce que tout le monde a le droit de faire
    }
        
    public function contest(){
        $this->set('jsIncludes',array('heights/d3.min','heights/jquery.viewport','heights/heights','logo_fly'));
        $this->set('cssIncludes',array('heights','bootstrap-image-gallery.min','blueimp-gallery.min'));
            
        //pour l'ajout de photo
    	if(!empty($this->request->data)){
            
            App::uses('AuthComponent', 'Controller/Component');
                
            $extension = strtolower(pathinfo($this->request->data['Height']['photo_file']['name'] , PATHINFO_EXTENSION));
                
            //taille en bytes
            $size = $this->request->data['Height']['photo_file']['size'];
                
            if(!empty($this->request->data['Height']['photo_file']['tmp_name'])){
                
                if(in_array($extension, array('jpg','jpeg','png'))&&($size<=3560938)){
                    
                    $user_id = $this->Auth->user('id');
                        
                    $this->request->data['Height']['user_id'] = $user_id;
                        
                    $this->Height->save($this->request->data);
                        
                    $file_name = $user_id . '-' . time() . '.' . $extension;
                    move_uploaded_file($this->request->data['Height']['photo_file']['tmp_name'], IMAGES . 'heights' . DS . $file_name);
                    $this->Height->saveField('url',$file_name);
                        
                    //redimensionnement de la photo
                    App::import('Vendor', 'ImageTool');
                        
                    //on effectue la rotation de l'image a partir des données EXIF de la photo
                    $image = ImageTool::autorotate(array(
                                'input' => IMAGES . 'heights' . DS . $file_name,
                                'output' => null
                            ));
                                
                    //on réduit l'image pour la miniature
                    $status_S = ImageTool::resize(array(
                        'input' => $image,
                        'output' => IMAGES . 'heights' . DS . 'S_' . $file_name,
                        'width' => 75,
                        'height' => 75,
                        'crop' => true,
                        'keepRatio' => false,
                        'paddings' => false,
                    ));
                        
                    //on reduit l'image pour l'affichage dans la galerie
                    $status_L = ImageTool::resize(array(
                        'input' => $image,
                        'output' => IMAGES . 'heights' . DS . $file_name,
                        'width' => 800,
                        'height' => 800,
                        'keepRatio' => true,
                        'paddings' => false,
                    ));
                    
                    $this->Session->setFlash(__("Ton avion a bien été envoyé ! Nous calculons son altitude au plus vite."), 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-success'
                    ));
                }
                else{
                    $this->Session->setFlash(__("Seuls les fichiers jpg, jpeg et png de moins de 3,4 Mo sont autorisés."), 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-danger'
                    ));
                }
                $this->redirect(array('controller' => 'heights', 'action' => 'contest'));
            }
        }
    }
        
    public function get_heights_gallery(){
        
    //TODO 
//        $this->request->onlyAllow('ajax');
    
        $conditions = array();
            
        $conditions['Height.verified'] = 1;
            
        if(!empty($this->request->data['school_id'])){
            $conditions['User.school_id'] = $this->request->data['school_id'];
        }
            
        $this->set('photos', $this->Height->find('all', array(
                    'conditions' => $conditions,
                    'order' => array('Height.created' => 'DESC'))));
                        
        $this->render('/Elements/heights_gallery');
    }
        
    public function get_heights(){
        //TODO 
//        $this->request->onlyAllow('ajax');
    
        $this->set('heights', $this->Height->find('all', array(
                    'conditions' => array('Height.verified' => 1),
                    'group' => 'User.school_id',
                    'order' => array('SUM(height)' => 'DESC'),
                    'fields' => array('User.school_id','SUM(height) AS total' ))));
        //recupere les ecoles
        $this->set('school_names',$this->Height->User->School->find('list'));
        $this->set('school_colors',$this->Height->User->School->find('list',array(
                                                            'fields' => array('School.color'))));
    }
        
}
?>