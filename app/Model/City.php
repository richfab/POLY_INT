<?php
App::uses('AuthComponent', 'Controller/Component');

class City extends AppModel {

    public $belongsTo = 'Country';
    public $hasMany = 'Experience';

    public $validate = array(
        'name' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', '50'),
                'message' => 'Le lieu doit comporter 50 caractères au maximum',
                'allowEmpty' => false
            )
        ),
        'lat' => array(
            'isnumeric' => array(
                'rule'    => 'numeric',
                'message' => 'Veuillez renseigner un lieu proposé dans la liste au moment de la saisie'
            )
        ),
        'lon' => array(
            'isnumeric' => array(
                'rule'    => 'numeric',
                'message' => 'Veuillez renseigner un lieu proposé dans la liste au moment de la saisie'
            )
        )
    );
    
    public function beforeSave($options = array()) {
        return true;
    }
}
?>