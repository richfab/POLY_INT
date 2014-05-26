<?php
App::uses('AppController', 'Controller');

class DashboardsController extends AppController {
        
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('index')); //ce que tout le monde a le droit de faire
    }
    
    //vue qui permet de voir seulement les statistiques du site
    public function index() {
        //on charge la vue admin_index mais les actions ne seront pas autorisées si on n'est pas admin
        $this->admin_index();
    }
    
    public function admin_index() {
        
        App::import('Controller', 'Users');
        $usersController = new UsersController;
        //nombre d'utilisateurs actifs
        $this->set('users_count',$usersController->User->find('count',array(
            'conditions' => array('User.active' => 1, 'User.role' => 'user')
        )));
        //classés par école
        $this->set('schools',$usersController->User->School->find('all'));
        //nombre de demandes d'inscription
        $this->set('signup_requests_count',$usersController->User->find('count',array(
            'conditions' => array('User.email' => NULL)
        )));
        
        App::import('Controller', 'Experiences');
        $experiencesController = new ExperiencesController;
        //nombre total d'expériences
        $this->set('experiences_count',$experiencesController->Experience->find('count'));
        //nombre de villes avec des expériences
        $this->set('cities_count',count($experiencesController->Experience->find('all',array(
            'group' => 'Experience.city_id'
        ))));
        //nombre de pays avec des expériences
        $this->set('countries_count',count($experiencesController->Experience->find('all',array(
            'group' => 'City.country_id'
        ))));
        
        App::import('Controller', 'Recommendations');
        $recommendationsController = new RecommendationsController;
        //nombre total de recommendations
        $this->set('recommendations_count',$recommendationsController->Recommendation->find('count'));
        //classés par type de recommandation
        $this->set('recommendationtypes',$recommendationsController->Recommendation->Recommendationtype->find('all'));
        
        $this->render('admin_index');
    }
}
?>