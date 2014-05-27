<?php
App::uses('AuthComponent', 'Controller/Component');

class Height extends AppModel {
    
    public $displayField = 'id';

    public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => array('User.role' => 'user','User.active' => '1'),
			'fields' => '',
			'order' => ''
		)
	);

    
    public function beforeSave($options = array()) {
        return true;
    }
}
?>