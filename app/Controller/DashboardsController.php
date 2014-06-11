<?php
App::uses('AppController', 'Controller');

class DashboardsController extends AppController {
    
    /**
    * This method is called before the controller action. It is useful to define which actions are allowed publicly.
    *
    * @return void
    */
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('index')); //actions that anyone is allowed to call
    }
    
    /**
    * This method allows anyone to see website stats but doesnt allow actions
    *
    * @return void
    */
    public function index() {
        //on charge la vue admin_index mais les actions ne seront pas autorisées si on n'est pas admin
        $this->admin_index();
    }
    
    /**
    * This method allows admin to see website stats and interacts with them
    *
    * @return void
    */
    public function admin_index() {
        
        App::import('Controller', 'Users');
        $usersController = new UsersController;
        //number of active users
        $this->set('users_count',$usersController->User->find('count',array(
            'conditions' => array('User.active' => 1, 'User.role' => 'user')
        )));
        //ordered by school
        $this->set('schools',$usersController->User->School->find('all'));
        //number of signup requests
        $this->set('signup_requests_count',$usersController->User->find('count',array(
            'conditions' => array('User.email' => NULL)
        )));
        
        App::import('Controller', 'Experiences');
        $experiencesController = new ExperiencesController;
        //number of experiences
        $this->set('experiences_count',$experiencesController->Experience->find('count'));
        //number of cities where there are experiences
        $this->set('cities_count',count($experiencesController->Experience->find('all',array(
            'group' => 'Experience.city_id'
        ))));
        //number of countries where there are experiences
        $this->set('countries_count',count($experiencesController->Experience->find('all',array(
            'group' => 'City.country_id'
        ))));
        
        App::import('Controller', 'Recommendations');
        $recommendationsController = new RecommendationsController;
        //number of recommendations
        $this->set('recommendations_count',$recommendationsController->Recommendation->find('count'));
        //ordered by recommendation types
        $this->set('recommendationtypes',$recommendationsController->Recommendation->Recommendationtype->find('all'));
        
        $this->render('admin_index');
    }
}
?>