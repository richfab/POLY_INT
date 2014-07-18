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
    * This method is called before the controller action. It is useful to define which actions are allowed publicly.
    *
    * @return void
    */
    public function beforeFilter() {
        parent::beforeFilter();
    }
        
    /**
    * This method gets activities
    * 
    * @return void
    */
    public function get_activities(){
        
//        $this->request->onlyAllow('ajax');
        
        //includes scripts
        $this->set('jsIncludes',array('photo_gallery'));
        $this->set('cssIncludes',array('blueimp-gallery'));
        
        //tableau des activités
        $activities = array();
        //tableau des user_ids des utilisateurs ayant déjà une activité récente
        $activities_user_ids = array();
        
        //recuperation des dernieres photos postees
        App::import('Controller', 'Photos');
        $photosController = new PhotosController;
            
        $photos_users = $photosController->Photo->find('all', array(
            'limit' => 5,
            'order' => 'MAX(Photo.created) DESC',
            'fields' => array('Experience.user_id'),
            'group' => 'Experience.user_id'
        ));
        
        foreach ($photos_users as $photos_user){
            array_push($activities, $photosController->Photo->find('first', array(
                'order' => 'Photo.created DESC',
                'conditions' => array('user_id' => $photos_user['Experience']['user_id']),
                'recursive' => 2
            )));
            
            array_push($activities_user_ids, $photos_user['Experience']['user_id']);
        }
        
        //recuperation des dernieres recpmmendations postees
        App::import('Controller', 'Recommendations');
        $recommendationsController = new RecommendationsController;
            
        $recommendations_users = $recommendationsController->Recommendation->find('all', array(
            'limit' => 5,
            'order' => 'MAX(Recommendation.created) DESC',
            'fields' => array('Experience.user_id'),
            'group' => 'Experience.user_id',
            'conditions' => array(
                "NOT" => array("Experience.user_id" => $activities_user_ids)
            )
        ));
        
        foreach ($recommendations_users as $recommendations_user){
            array_push($activities, $recommendationsController->Recommendation->find('first', array(
                'order' => 'Recommendation.created DESC',
                'conditions' => array('user_id' => $recommendations_user['Experience']['user_id']),
                'recursive' => 2
            )));
            
            array_push($activities_user_ids, $recommendations_user['Experience']['user_id']);
        }
    
        //recuperation des dernieres experiences postees
        App::import('Controller', 'Experiences');
        $experiencesController = new ExperiencesController;
            
        $experiences_users = $experiencesController->Experience->find('all', array(
            'limit' => 3,
            'order' => 'MAX(Experience.created) DESC',
            'fields' => array('Experience.user_id'),
            'group' => 'Experience.user_id',
            'conditions' => array(
                "NOT" => array("Experience.user_id" => $activities_user_ids)
            ),
            'recursive' => 0
        ));
        
        foreach ($experiences_users as $experiences_user){
            array_push($activities, $experiencesController->Experience->find('first', array(
                'order' => 'Experience.created DESC',
                'conditions' => array('user_id' => $experiences_user['Experience']['user_id']),
                'recursive' => 0
            )));
            
            array_push($activities_user_ids, $experiences_user['Experience']['user_id']);
        }
        
        //recuperation des dernieres utilisateurs inscrits
        App::import('Controller', 'Users');
        $usersController = new UsersController;
            
        $users = $usersController->User->find('all', array(
            'limit' => 2,
            'order' => 'User.created DESC',
            'conditions' => array('User.active' => 1, "NOT" => array("User.id" => $activities_user_ids)),
            'recursive' => 0
        ));
        
        foreach ($users as $user){
            array_push($activities,$user);
        }
        
        //melange aléatoire des activités
        shuffle($activities);
            
        //sets last activities
        $this->set(array('activities' => $activities));
    }
        
}
?>