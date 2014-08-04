<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
    
    /**
    * This variable defines the components that can be used everywhere in the app
    *
    * @return void
    */
    public $components = array(
        'Paginator',
        'Session',
        'Auth' => array(
            'loginRedirect' => '/',
            'logoutRedirect' => '/',
            'authorize' => array('Controller'),
            'authenticate' => array(
                'Form' => array(
                    'fields' => array(
                        'username' => 'email', 'password' => 'password'
                    )
                )
            ),
            'flash' => array(
                'element' => 'alert',
                'key' => 'auth',
                'params' => array(
                        'plugin' => 'BoostCake',
                        'class' => 'alert-error'
                )
            )
        )
    );
    
    /**
    * This variable defines which helpers to use
    *
    * @return void
    */
    public $helpers = array(
            'Js' => array('Jquery'),
            'Session',
            'Html' => array('className' => 'BoostCake.BoostCakeHtml'),
            'Form' => array('className' => 'BoostCake.BoostCakeForm'),
            'Paginator' => array('className' => 'BoostCake.BoostCakePaginator'),
    );
    
    /**
    * This method is called before the controller action. It is useful to define which actions are allowed publicly.
    *
    * @return void
    */
    public function beforeFilter() {
        
        if ($this->Session->check('Config.language')) {
            Configure::write('Config.language', $this->Session->read('Config.language'));
        }
        
        // Définition de la locale pour toutes les fonctions php relatives à la de gestion du temps :
        if (Configure::read('Config.language') === 'fra') {
            setlocale(LC_TIME, 'fr_FR');
        }
        else {
            setlocale(LC_TIME, 'en_US');
        }
        
    	//authorizes anyone to see static pages
    	$this->Auth->allow(array('display','switchLanguage'));
    	
    	if(isset($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin'){
            if($this->Auth->user('role') == 'admin'){
                $this->layout = 'admin';
            }
        }
    }
    
    /**
    * This method determines whether a user is allowed to call action
    * 
    * @param string $user
    * @return void
    */
    public function isAuthorized($user = null) {
    	
        //any logged in user can access public actions
        if (empty($this->request->params['admin'])) {
            return true;
        }
	
        // Admin can access every action
        if (isset($user['role']) && $user['role'] === 'admin') {
            return true;
        }

        // Default deny
        return false;
    }

    /**
    * This method enables previous page calls
    *
    * @return void
    */
    public function beforeRender() {
        $this->set('refer',$this->referer);
    }
    
    /**
    * This method switches between english and french
    *
    * @return void
    */
    public function switchLanguage(){
        
        if (Configure::read('Config.language') === 'fra') {
            $this->Session->write('Config.language', 'eng');
        }
 
        else {
            $this->Session->write('Config.language', 'fra');
        }
 
        //in order to redirect the user to the page from which it was called
        return $this->redirect($this->referer());
    }

}
