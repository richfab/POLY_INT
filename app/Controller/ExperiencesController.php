<?php
class ExperiencesController extends AppController {

    public $helpers = array('Html', 'Form');
    
    public $components = array("RequestHandler");
    
    /* Set pagination options */
	public $paginate = array(
		'limit' => 20,
		'order' => array('created' => 'desc')
	);
	
	public function beforeFilter() {
		parent::beforeFilter();
	    $this->Auth->allow('index','index_gmaps','get_positions','photos'); //tout le monde a le droit de voir la carte et les positions des participants
	}
	
	//get positions json
	public function get_positions() {
		
	
		if($this->RequestHandler->isAjax()){
			
			//si la requete json n'a aucune année de précisée, alors on recupere la derniere année existante dans la base de données
			if(empty($this->request->data['year'])){
				$year = $this->Position->User->Year->find('first',array('order' => array('Year.id' => 'DESC'),
																	'fields' => 'Year.id'));
				$year_id = $year['Year']['id'];
			}
			else{
				$year_id = $this->request->data['year'];
			}
			
			//si la requete json n'a aucune école de précisée, alors on recupere toutes les écoles dans la base de données
			if(empty($this->request->data['school_id'])){
				$conditions = array('User.year_id' => $year_id,
	        						'User.role' => 'user',
									'User.payed' => true);
			}
			else{
				$school_id = $this->request->data['school_id'];
				$conditions = array('User.year_id' => $year_id,
									'User.school_id' => $school_id,
	        						'User.role' => 'user',
									'User.payed' => true);
			}
			
			//on recupere les positions de tous les utilisateurs de type user, qui ont payé et qui sont dans l'année sélectionnée, ordonnés par distance décroissante
	        $users = $this->Position->User->find('all',array(
	        	'order' => array('User.distance' => 'DESC'),
	        	'fields' => array('User.team_name','User.team_number','User.member_name_one','User.member_name_two','User.distance','School.name','School.lat','School.lon'),
	        	'conditions' => $conditions));
									
	        return new CakeResponse(array('body'=> json_encode(compact('users', 'positions')),'status'=>200));
        }
    }
    
    public function index() {
    			//on inclut le script mapquest, celui responsable du chargement de la carte et celui pour la maj position
    	$this->set('jsIncludes',array('http://maps.googleapis.com/maps/api/js?key=AIzaSyA7d3ppUSRkOeGZ-ZAmU0R8f_PxyheJch0&sensor=true&libraries=geometry','map','overlays','upload_position','tools','loader','bootstrap.min','hook/mousewheel','hook/hook.modif'));
    	
    	$this->set('cssIncludes',array('hook/hook.css?v=1','instant-sprite'));
    	
    	//on recupere la derniere annee pour savoir si la course est en cours
    	$year = $this->Position->User->Year->find('first',array('order' => array('Year.id' => 'DESC'),
																	'fields' => 'Year.en_cours'));
		$en_cours = $year['Year']['en_cours'];
    	$this->set('en_cours',$en_cours);
    	
    	//on recupere toutes les années pour le selector
    	$this->Position->User->Year->recursive = 0;
    	$years = $this->Position->User->Year->find('all',array('order' => array('Year.id' => 'DESC')));
    	$this->set('years',$years);
    	
    	//on recupere toutes les écoles pour le selector
    	$this->Position->User->School->recursive = 0;
    	$schools = $this->Position->User->School->find('all',array('order' => array('School.name' => 'ASC')));
    	$this->set('schools',$schools);
    	
    	$user_id = $this->Auth->user('id');
    	
    	//pour la distance
    	//on recupere la position de son école
        $this->Position->User->recursive = 0;
        $school = $this->Position->User->find('first', array('conditions' => array('User.id' => $user_id),
        															'fields' => array('School.lat', 'School.lon')));
        $this->set('school',$school);
    	
    	
    	//pour l'ajout de photo
    	if(!empty($this->request->data)){
    		
    		$position = $this->Position->find('first',array('order' => array('Position.modified' => 'DESC'),
																	'fields' => 'Position.id',
																	'conditions' => array('Position.user_id' => $user_id)));
			$position_id = $position['Position']['id'];
			
			$this->request->data['Position']['id']=$position_id;
			
			$this->Position->save($this->request->data);
    	
    		$extension = strtolower(pathinfo($this->request->data['Position']['photo_file']['name'] , PATHINFO_EXTENSION));
    		
    		//taille en bytes
    		$size = $this->request->data['Position']['photo_file']['size'];
    	
	    	if(!empty($this->request->data['Position']['photo_file']['tmp_name'])){
	    	
	    		if(in_array($extension, array('jpg','jpeg','png'))&&($size<=3560938)){
	    		
		    		$file_name = $user_id . '-' . time() . '.' . $extension;
		    		move_uploaded_file($this->request->data['Position']['photo_file']['tmp_name'], IMAGES . 'photos' . DS . $file_name);
			    	$this->Position->saveField('photo_url',$file_name);
			    	
			    	//redimensionnement de la photo
			    	App::import('Vendor', 'ImageTool');
			    	
			    	//on effectue la rotation de l'image a partir des données EXIF de la photo
			    	$image = ImageTool::autorotate(array(
					    'input' => IMAGES . 'photos' . DS . $file_name,
					    'output' => null
					));
			    	
			    	//on réduit l'image pour l'affichage sur la carte
			    	$status_S = ImageTool::resize(array(
					    'input' => $image,
					    'output' => IMAGES . 'photos' . DS . 'S_' . $file_name,
					    'width' => 200,
					    'height' => 200,
					    'crop' => true,
					    'keepRatio' => false,
					    'paddings' => false,
					));
					
					//on reduit l'image pour l'affichage dans la galerie
					$status_L = ImageTool::resize(array(
					    'input' => $image,
					    'output' => IMAGES . 'photos' . DS . $file_name,
					    'width' => 800,
					    'height' => 800,
					    'keepRatio' => true,
					    'paddings' => false,
					));
		    	}
		    	else{
			    	$this->Session->setFlash("Seuls les fichiers jpg, jpeg et png de moins de 3,4 Mo sont autorisés.", 'default', array('class' => 'alert-box radius warning'));
			    	return $this->redirect(array('action' => 'index'));
		    	}
	    	}
    	}
    	
	
    }
    
    public function photos() {

       	$this->set('positions', $this->Paginate('Position',array('User.role' => 'user','Position.photo_url !=' => NULL)));

    }
    
    public function add(){
    	App::uses('AuthComponent', 'Controller/Component');
    	
    	//importation et instantiation du controller users pour l'appel a la methode userHasPayed()
    	App::import('Controller', 'Users');
    	$UsersController = new UsersController;
    	
    	$user_id = $this->Auth->user('id');
    
    	//on verifie que l'utilisateur connecté a payé
    	if(!$UsersController->userHasPayed($user_id)){
    		return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>'Votre inscription doit avoir été validée pour pouvoir mettre à jour votre position.')),'status'=>500));
    	}
    	
    	//on verifie que l'utilisateur fait bien partie de l'année en cours
    	
   		if(!$UsersController->userBelongsToCurrentYear($user_id)){
			return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>"Vous n'êtes pas inscrit pour l'année en cours.")),'status'=>500));
		}
    
	    if($this->RequestHandler->isAjax()){
	    	
	    	$this->Position->create();
	    	
	    	$this->Position->user_id = $user_id;
	    	$this->Position->lat = $this->request->data['lat'];
	    	$this->Position->lon = $this->request->data['lon'];
	    	$this->Position->lieu = $this->request->data['lieu'];
	    	$this->Position->distance = $this->request->data['distance'];
	    	
	        if ($this->Position->save($this->Position) && $this->update_distance($user_id)) {
	            return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>0)),'status'=>200));
	        }else{
	            return new CakeResponse(array('body'=> json_encode(array('errorMessage'=>'Une erreur est survenue lors de l\'envoi de la position, veuillez réessayer.')),'status'=>500));
	        }
	    }
    }
    
    //fonction qui met a jour la distance max d'un equipage et qui lui retire ses penalités de retard
    public function update_distance($user_id = null){
    	if($user_id!=null){
    		//on recupere la position la plus eloignée du point de départ
	    	$position_max = $this->Position->find('first', array('conditions' => array('Position.user_id' => $user_id),
	    														'order' => array('Position.distance' => 'DESC'),
	    														'fields' => array('Position.distance')));
			
			//on récupère la pénalité éventuelle de l'équipage
			$this->Position->User->recursive = 0;
			$user = $this->Position->User->findById($user_id);
			$distance_penalty = $user['User']['distance_penalty'];
			
	    	if($position_max){
		    	$distance_max = $position_max['Position']['distance'] - $distance_penalty;
		    }
		    else{
			    $distance_max = 0;
		    }
	    	//on enregistre la distance max de l'equipe
		    $this->Position->User->id = $user_id;
		    return $this->Position->User->saveField('distance',$distance_max);
	    }
	    return false;
    }

    public function admin_index($year = null, $user_id = null) {
	    
	    //on recupere l'equipage pour le breadcrumbs
	    $this->Position->User->recursive = 0;
    	$this->set('user',$this->Position->User->find('first', array('conditions' => array('User.id' => $user_id))));
    	
        $this->Position->recursive = 0;
        //on ne recupere que les position de l'utilisateur
        $this->set('positions', $this->Paginate('Position',array('user_id' => $user_id)));
    }
    
    public function admin_add($year = null, $user_id = null) {
    
    	//on inclut le script google maps pour l'autocomplete des lieux et celui de la distance
    	$this->set('jsIncludes',array('http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places,geometry','places_autocomplete','tools'));
    	
    	//on recupere l'equipage pour le breadcrumbs
	    $this->Position->User->recursive = 0;
    	$this->set('user',$this->Position->User->find('first', array('conditions' => array('User.id' => $user_id))));
    
        //on recupere la position de son école
        $this->Position->User->recursive = 0;
        $school = $this->Position->User->find('first', array('conditions' => array('User.id' => $user_id),
        															'fields' => array('School.lat', 'School.lon')));
        $this->set('school',$school);
        
        $this->request->data['Position']['user_id']=$user_id;
        
        if ($this->request->is('post')) {
        	
            $this->Position->create();
            if ($this->Position->save($this->request->data)&&$this->update_distance($user_id)) {
                $this->Session->setFlash("Les modifications ont bien été enregistrées", 'default', array('class' => 'alert-box radius alert-success'));
                return $this->redirect(array('action' => 'index',$year,$user_id));
            }
            $this->Session->setFlash("Erreur lors de l'enregistrement", 'default', array('class' => 'alert-box radius warning'));
        }
    }
    
    public function admin_edit($year = null, $user_id = null, $id = null) {
    
    	//on inclut le script google maps pour l'autocomplete des lieux
    	$this->set('jsIncludes',array('http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places,geometry','places_autocomplete','tools'));
    	
    	//on recupere l'equipage pour le breadcrumbs
	    $this->Position->User->recursive = 0;
    	$this->set('user',$this->Position->User->find('first', array('conditions' => array('User.id' => $user_id))));
    	
    	//on recupere la position de son école
        $this->Position->User->recursive = 0;
        $school = $this->Position->User->find('first', array('conditions' => array('User.id' => $user_id),
        															'fields' => array('School.lat', 'School.lon')));
        $this->set('school',$school);
    
        $this->Position->id = $id;
        
        if (!$this->Position->exists()) {
            throw new NotFoundException(__("Cette position n'existe pas"));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Position->save($this->request->data)&&$this->update_distance($user_id)) {
                $this->Session->setFlash("Les modifications ont bien été enregistrées", 'default', array('class' => 'alert-box radius alert-success'));
                return $this->redirect(array('action' => 'index',$year,$user_id));
            }
            $this->Session->setFlash("Erreur lors de l'enregistrement", 'default', array('class' => 'alert-box radius warning'));
        } else {
            $this->request->data = $this->Position->read(null, $id);
        }
    }
    
    public function admin_delete($user_id = null, $id = null) {
        $this->request->onlyAllow('post');

        $this->Position->id = $id;
        if (!$this->Position->exists()) {
            throw new NotFoundException(__("Cette position n'existe pas"));
        }
        if ($this->Position->delete()&&$this->update_distance($user_id)) {
            $this->Session->setFlash("La position a bien été supprimée", 'default', array('class' => 'alert-box radius alert-success'));
            return $this->redirect($this->referer());
        }
        $this->Session->setFlash("La position n'a pas pu être supprimée", 'default', array('class' => 'alert-box radius warning'));
        return $this->redirect($this->referer());
    }
    
}
?>