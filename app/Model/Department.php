<?php
class Department extends AppModel {
    
    public $hasMany = array(
        'User' => array(
            'className' => 'User',
            'order' => 'User.lastname ASC'
        )
    );
	
    public $validate = array(
        'name' => array(
            'between' => array(
                'rule'    => array('between', 1, 50),
                'message' => 'Le nom doit comporter entre 1 et 50 caractères',
                'allowEmpty' => false
            ),
            'isUnique' => array(
                'rule'    => 'isUnique',
                'message' => "Ce nom de département est déjà utilisé"
            )
        )
    );
        
    public function beforeSave($options = array()) {
    	//majuscules au nom
        if (isset($this->data[$this->alias]['name'])) {
            $this->data[$this->alias]['name'] = ucwords(strtolower($this->data[$this->alias]['name']));
        }
        return true;
    }
}
?>