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
        $this->Auth->allow(array('index','graph')); //ce que tout le monde a le droit de faire
    }
    
    //ajoute une photo pour le concours de hauteur
    public function add(){
        //pour l'ajout de photo
    	if(!empty($this->request->data)){
            
            App::uses('AuthComponent', 'Controller/Component');
            
            $user_id = $this->Auth->user('id');

            $this->request->data['Height']['user_id'] = $user_id;

            $this->Height->save($this->request->data);

            $extension = strtolower(pathinfo($this->request->data['Height']['photo_file']['name'] , PATHINFO_EXTENSION));

            //taille en bytes
            $size = $this->request->data['Height']['photo_file']['size'];

            if(!empty($this->request->data['Height']['photo_file']['tmp_name'])){

                if(in_array($extension, array('jpg','jpeg','png'))&&($size<=3560938)){

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
                        'width' => 200,
                        'height' => 200,
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
                }
                else{
                    $this->Session->setFlash(__("Seuls les fichiers jpg, jpeg et png de moins de 3,4 Mo sont autorisés."), 'alert', array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-danger'
                    ));
                    return $this->redirect(array('action' => 'index'));
                }
            }
    	}
    }
    
    public function graph(){
        $this->set('jsIncludes',array('heights/d3.min','heights/jquery.viewport','heights/heights'));
        $this->set('cssIncludes',array('heights'));
    }
    
    public function get_heights(){
        
//        $this->request->onlyAllow('ajax');
        App::uses('AuthComponent', 'Controller/Component');
        
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