<?php
App::uses('AuthComponent', 'Controller/Component');

class Experience extends AppModel {

    public $belongsTo = 'User';
//    public $hasOne = array('Motive','City');

    public $validate = array(
        'dateStart' => array(
            'dateformat' => array(
                'rule'    => 'notEmpty',
                'message' => 'La date doit être au format 07-04-2014 13:21',
                'allowEmpty' => false
            )
        ),
        'dateEnd' => array(
            'dateformat' => array(
                'rule'    => 'notEmpty',
                'message' => 'La date doit être au format 07-04-2014 13:21',
                'allowEmpty' => false
            )
        ),
        'description' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', '300'),
                'message' => 'La description doit comporter 300 caractères au maximum',
                'allowEmpty' => true
            )
        ),
        'note' => array(
        	'number' => array(
                    'rule'       => array('range', 0, 6),
                    'message'    => 'Veuillez entrer une note entre 1 et 5',
                    'allowEmpty' => true
                )
        ),
        'comment' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', '10000'),
                'message' => 'Le commentaire doit comporter 10000 caractères au maximum',
                'allowEmpty' => true
            )
        )
    );
    
    public function beforeSave($options = array()) {
	    return true;
	}
}
?>