<?php
App::uses('AppModel', 'Model');
/**
 * Recommendation Model
 *
 * @property Experience $Experience
 * @property Recommendationtype $Recommendationtype
 */
class Photo extends AppModel {

/**
 * belongsTo associations
 *
 * @var array
 */
    public $belongsTo = array(
            'Experience' => array(
                    'className' => 'Experience',
                    'foreignKey' => 'experience_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => ''
            )
    );
    
    public $validate = array(
        'caption' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', '10000'),
                'message' => 'La description doit comporter 10000 caractères au maximum',
                'allowEmpty' => true
            )
        ),
        'source' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', '255'),
                'message' => 'La source doit comporter 255 caractères au maximum',
                'required' => true,
            )
        )
    );
    
    public function beforeSave($options = array()) {
        return true;
    }    
        
}
