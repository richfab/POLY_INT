<?php
/**
 * Activities Controller
 *
 * @property Activity $Activity
 * @property PaginatorComponent $Paginator
 */
App::uses('AppController', 'Controller');
    
/**
 * Experiences Controller
 *
 * This class defines all actions relative to Experiences
 *
 * @package		app.Controller
 */
class ActivitiesController extends AppController {
    
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
    * This method allows user to view activities
    *
    * @return void
    */
    public function index(){
        
    	$this->set('jsIncludes',array('photo_gallery','get_activities','readmore','logo_fly'));
        $this->set('cssIncludes',array('blueimp-gallery'));
        
    }
        
    /**
    * This method gets activities
    * 
    * @return void
    */
    public function get_activities(){
        
        $this->request->onlyAllow('ajax');
        
        $offset = $this->request->data['offset'];
        
        //tableau des activités
        $activities = array();
        
        //recuperation des dernieres utilisateurs inscrits
        App::import('Controller', 'Users');
        $usersController = new UsersController;
        
        //nombre d'utilisateurs a recuperer
        $user_limit = 4;
            
        $users = $usersController->User->find('all', array(
            'limit' => $user_limit,
            'offset' => $user_limit * $offset,
            'order' => 'User.created DESC',
			'conditions' => array('User.active' => 1),
            'recursive' => 0
        ));
        
        foreach ($users as $user){
            $user['Activity']['type'] = 'user';
            $user['Activity']['created'] = $user['User']['created'];
            $user['Activity']['User'] = $user['User'];
            array_push($activities,$user);
        }
        
        //recuperation des dernieres recommendations postees
        App::import('Controller', 'Recommendations');
        $recommendationsController = new RecommendationsController;
        
        //nombre de recommendations a recuperer
        $recommendation_limit = 7;
            
        $recommendations_users = $recommendationsController->Recommendation->find('all', array(
            'limit' => $recommendation_limit,
            'offset' => $recommendation_limit * $offset,
            'order' => 'MAX(Recommendation.created) DESC',
            'fields' => array('Experience.user_id'),
            'group' => 'Experience.user_id'
        ));
        
        foreach ($recommendations_users as $recommendations_user){
            $recommendation = $recommendationsController->Recommendation->find('first', array(
                'order' => 'Recommendation.created DESC',
                'conditions' => array('user_id' => $recommendations_user['Experience']['user_id']),
                'recursive' => 2
            ));
            $recommendation['Activity']['type'] = 'recommendation';
            $recommendation['Activity']['created'] = $recommendation['Recommendation']['created'];
            $recommendation['Activity']['people_around'] = $usersController->get_people_around($recommendation['Experience']['city_id']);
            $recommendation['Activity']['User'] = $recommendation['Experience']['User'];
            array_push($activities, $recommendation);
        }
        
        //recuperation des recommendationtypes
        //sets recommendationtypes (icons et names)
        $this->set('recommendationtype_names',$recommendationsController->Recommendation->Recommendationtype->find('list'));
        $this->set('recommendationtype_icons',$recommendationsController->Recommendation->Recommendationtype->find('list',array(
            'fields' => array('Recommendationtype.icon')
        )));
    
        //recuperation des dernieres experiences postees
        App::import('Controller', 'Experiences');
        $experiencesController = new ExperiencesController;
        
        //nombre de recommendations a recuperer
        $experience_limit = 6;
            
        $experiences_users = $experiencesController->Experience->find('all', array(
            'limit' => $experience_limit,
            'offset' => $experience_limit * $offset,
            'order' => 'MAX(Experience.created) DESC',
            'fields' => array('Experience.user_id'),
            'group' => 'Experience.user_id',
            'recursive' => 0
        ));
        
        foreach ($experiences_users as $experiences_user){
            $experience = $experiencesController->Experience->find('first', array(
                'order' => 'Experience.created DESC',
                'conditions' => array('user_id' => $experiences_user['Experience']['user_id']),
                'recursive' => 0
            ));
            $experience['Activity']['type'] = 'experience';
            $experience['Activity']['created'] = $experience['Experience']['created'];
            $experience['Activity']['people_around'] = $usersController->get_people_around($experience['Experience']['city_id']);
            $experience['Activity']['User'] = $experience['User'];
            array_push($activities, $experience);
        }
        
        //tri des activités par date de création
        $sort_by_date = array();
        foreach ($activities as $activity => $row)
        {
            $sort_by_date[$activity] = $row['Activity']['created'];
        }
        array_multisort($sort_by_date, SORT_DESC, $activities);
            
        //sets last activities
        $this->set(array('activities' => $activities));
        //sets offset
        $this->set(array('offset' => $offset+1));
        
        App::import('Controller', 'Countries');
        $countriesController = new CountriesController;
        $this->set('countries',$countriesController->Country->find('list'));
        
        //recuperation des coleurs des ecoles
        $this->set('school_colors',$usersController->User->School->find('list',array(
                                                                'fields' => array('School.color'))));
        
        $this->layout = 'ajax';
    }
        
}
?>