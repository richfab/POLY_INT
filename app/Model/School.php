<?php
class School extends AppModel {
    
    public $hasMany = array(
        'User' => array(
            'className' => 'User',
            'order' => 'User.lastname ASC',
            'conditions' => array('User.role' => 'user','User.active' => '1')
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
                'message' => "Ce nom d'école est déjà utilisé"
            )
        ),'email_domains' => array(
            'between' => array(
                'rule'    => array('between', 0, 255),
                'message' => 'Les noms de domaines doivent comporter au maximum 255 charactères',
                'allowEmpty' => true
            ),
            'domain_format' => array(
                'rule' => '/^@/',
                'message' => "Les noms de domaine d'email doivent commencer par '@'. Ex : @domain1.fr,@domain2.com"
            )
        ),
        'color' => array(
            'between' => array(
                'rule'    => array('between', 1, 7),
                'message' => 'La couleur doit être du type FFFFFF',
                'allowEmpty' => false
            )
        )
    );
        
    public function beforeSave($options = array()) {
    	
        return true;
    }
}
?>