<?php
App::uses('AuthComponent', 'Controller/Component');

class Experience extends AppModel {

    public $belongsTo = array('User','Motive','City');

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
        'input' => array(
            'required' => array(
                'rule'    => 'notEmpty',
                'message' => 'Veuillez renseigner un lieu',
                'allowEmpty' => false
            )
        ),
        'establishment' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', '140'),
                'message' => "L'établissement doit comporter 140 caractères au maximum",
                'allowEmpty' => true
            )
        ),
        'description' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', '140'),
                'message' => 'La description doit comporter 140 caractères au maximum',
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