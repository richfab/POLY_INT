<?php
/**
 * Heights Controller
 *
 * @property Height $Height
 * @property PaginatorComponent $Paginator
 */
App::uses('AppController', 'Controller');

/**
 * Heights Controller
 *
 * This class defines all actions relative to the height contest
 *
 * @package		app.Controller
 */
class HeightsController extends AppController {
    
    /**
    * Component for json extension
    *
    * @var array
    */
    public $components = array("RequestHandler");
        
    /**
    * Pagination options
    *
    * @var array
    */
    public $paginate = array(
            'limit' => 20,
            'order' => array('dateEnd' => 'DESC'),
            'conditions' => array('User.active'=>'1')
    );
    
    /**
    * This method is called before the controller action. It is useful to define which actions are allowed publicly.
    *
    * @return void
    */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('contest','get_heights','get_heights_gallery')); //actions that anyone is allowed to call
    }
    
    /**
    * This method allows anyone to see the contest gallery and graph
    *
    * @return void
    */
    public function contest(){
        $this->set('jsIncludes',array('heights/d3.min','heights/jquery.viewport','heights/heights','logo_fly'));
        $this->set('cssIncludes',array('heights','blueimp-gallery'));
        
        //recupere les ecoles
        $this->set('school_names',$this->Height->User->School->find('list'));
            
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
                    $this->request->data['Height']['place'] = ucwords($this->request->data['Height']['place']);
                        
                    $this->Height->save($this->request->data);
                        
                    $file_name = $user_id . '-' . time() . '.' . $extension;
                    move_uploaded_file($this->request->data['Height']['photo_file']['tmp_name'], IMAGES . 'heights-photos' . DS . $file_name);
                    $this->Height->saveField('url',$file_name);
                        
                    //redimensionnement de la photo
                    App::import('Vendor', 'ImageTool');
                        
                    //on effectue la rotation de l'image a partir des données EXIF de la photo
                    $image = ImageTool::autorotate(array(
                                'input' => IMAGES . 'heights-photos' . DS . $file_name,
                                'output' => null
                            ));
                                
                    //on réduit l'image pour la miniature
                    $status_S = ImageTool::resize(array(
                        'input' => $image,
                        'output' => IMAGES . 'heights-photos' . DS . 'S_' . $file_name,
                        'width' => 75,
                        'height' => 75,
                        'crop' => true,
                        'keepRatio' => false,
                        'paddings' => false,
                    ));
                        
                    //on reduit l'image pour l'affichage dans la galerie
                    $status_L = ImageTool::resize(array(
                        'input' => $image,
                        'output' => IMAGES . 'heights-photos' . DS . $file_name,
                        'width' => 800,
                        'height' => 800,
                        'keepRatio' => true,
                        'paddings' => false,
                    ));
                    
                    //envoi un email a l'admin
                    App::uses('CakeEmail','Network/Email');
                    $email = new CakeEmail('default');
                    $email->to('hello@polytech-abroad.com')
                            ->subject('Nouvelle photo Paper Plane Contest')
                            ->emailFormat('html')
                            ->template('new_photo_contest')
                            ->viewVars(array('img' => $file_name))
                            ->send();
                    
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
                $this->redirect('/contest');
            }
        }
    }
    
    /**
    * This method gets the data and renders the contest gallery
    *
    * @return void
    */
    public function get_heights_gallery(){
    
        $conditions = array();
            
        $conditions['Height.verified'] = 1;
        $conditions['Height.url !='] = NULL;
        $conditions['Height.created >='] = "2015-06-01";
            
        if(!empty($this->request->data['school_id'])){
            $conditions['User.school_id'] = $this->request->data['school_id'];
        }
            
        $this->set('photos', $this->Height->find('all', array(
                    'conditions' => $conditions,
                    'order' => array('Height.created' => 'DESC'))));
        $this->set('school_colors',$this->Height->User->School->find('list',array(
                                                            'fields' => array('School.color'))));
                        
        $this->render('/Elements/heights_gallery');
    }
    
    /**
    * This method gets the data and renders the contest graph
    *
    * @return void
    */
    public function get_heights(){

        $conditions = array();

        $conditions['Height.created >='] = "2015-06-01";
        $conditions['Height.verified'] = 1;
    
        $this->set('heights', $this->Height->find('all', array(
                    'conditions' => $conditions,
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