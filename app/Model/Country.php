<?php
App::uses('AuthComponent', 'Controller/Component');

class Country extends AppModel {

    public $hasMany = 'City';

    public $validate = array(
        'name' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', '50'),
                'message' => 'Le lieu doit comporter 50 caractères au maximum',
                'allowEmpty' => false
            )
        ),
        'code' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', '3'),
                'message' => 'Le lieu doit comporter 3 caractères au maximum',
                'allowEmpty' => false
            )
        )
    );
    
    public function beforeSave($options = array()) {
        return true;
    }
}
?>