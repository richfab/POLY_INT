<?php
App::uses('AuthComponent', 'Controller/Component');

class Motive extends AppModel {

    public $hasMany = 'Experience';

    public $validate = array(
        'name' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', '50'),
                'message' => 'Le motif doit comporter 50 caractères au maximum',
                'allowEmpty' => false
            )
        )
    );
    
    public function beforeSave($options = array()) {
        return true;
    }
}
?>