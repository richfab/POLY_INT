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

	public $components = array(
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
        
    public $helpers = array(
            'Js' => array('Jquery'),
            'Session',
            'Html' => array('className' => 'BoostCake.BoostCakeHtml'),
            'Form' => array('className' => 'BoostCake.BoostCakeForm'),
            'Paginator' => array('className' => 'BoostCake.BoostCakePaginator'),
    );

    public function beforeFilter() {
    
    	//on autorise les utilisateurs non loggés a voir les pages statiques
    	$this->Auth->allow(array('display'));
    	
    	if(isset($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin'){
            $this->layout = 'admin';
        }
        if($this->Auth->user('role') == 'admin'){
            $this->layout = 'admin';
        }
    }
    
    public function isAuthorized($user = null) {
    	// Chacun des utilisateurs enregistrés peut accéder aux fonctions publiques
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

    //pour les boutons retour
    public function beforeRender() {
        $this->set('refer',$this->referer);
    }

}
