<?php
App::uses('AuthComponent', 'Controller/Component');

class Typenotification extends AppModel {

    public $hasMany = array(
		'Experience' => array(
			'className' => 'Experience',
			'foreignKey' => 'motive_id'
		)
	);
    
    public function beforeSave($options = array()) {
        return true;
    }
}
?>