<?php
App::uses('AuthComponent', 'Controller/Component');
    
class User extends AppModel {
    
    public $displayField = 'email';
    
    public $actsAs = array(
        'Upload.Upload' => array(
            'fields' => array(
                'avatar' => 'img/avatars/:id1000/:id'
            )
        )
    );
        
    public $belongsTo = array(
		'School' => array(
			'className' => 'School',
			'foreignKey' => 'school_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Department' => array(
			'className' => 'Department',
			'foreignKey' => 'department_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
            
    public $hasMany = array(
		'Experience' => array(
			'className' => 'Experience',
			'foreignKey' => 'user_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => 'Experience.dateEnd DESC',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
            
    public $validate = array(
        'email' => array(
            'format' => array(
                'rule'       => 'email',
                'message'    => 'Entrez un email valide',
                'allowEmpty' => false
            ),
            'isUnique' => array(
                'rule'    => 'isUnique',
                'message' => 'Cet email est déjà utilisé'
            )
        ),
        'email_at_signup' => array(
            'format' => array(
                'rule'       => 'email',
                'message'    => 'Entrez un email valide',
                'allowEmpty' => false
            ),
            'isUnique' => array(
                'rule'    => 'isUnique',
                'message' => 'Cet email est déjà utilisé'
            )
        ),
        'avatar_file' => array(
            'rule' => array('fileExtension', array('jpg','png','jpeg'))
        ),
        'linkedin' => array(
            'adress' => array(
                'rule' => 'url',
                'message' => 'Entrez une adresse de profil LinkedIn valide',
                'allowEmpty' => true
            )
        ),
        'password' => array(
            'rule'    => array('minLength', '6'),
            'message' => 'Le mot de passe doit comporter 6 caractères au minimum',
            'allowEmpty' => false
        ),
        'password_confirm' => array(
            'rule'    => array('minLength', '6'),
            'message' => 'Le mot de passe doit comporter 6 caractères au minimum',
            'allowEmpty' => false
        ),
        'firstname' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', '20'),
                'message' => 'Le prénom doit comporter 20 caractères au maximum',
                'allowEmpty' => false
            )
        ),
        'lastname' => array(
            'maxLength' => array(
                'rule'    => array('maxLength', '20'),
                'message' => 'Le nom doit comporter 20 caractères au maximum',
                'allowEmpty' => false
            )
        )
    );
        
    //chiffrage du mot de passe avant enregistrement
    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['password'])) {
            $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
        }
        if (isset($this->data[$this->alias]['firstname'])) {
            $this->data[$this->alias]['firstname'] = ucwords(strtolower($this->data[$this->alias]['firstname']));
        }
        if (isset($this->data[$this->alias]['lastname'])) {
            $this->data[$this->alias]['lastname'] = ucwords(strtolower($this->data[$this->alias]['lastname']));
        }
        if (isset($this->data[$this->alias]['linkedin']) && $this->data[$this->alias]['linkedin'] !== '') {
            if(substr($this->data[$this->alias]['linkedin'],0,4) !== 'http'){
                $this->data[$this->alias]['linkedin'] = 'https://'.$this->data[$this->alias]['linkedin'];
            }
        }
        return true;
    }
}
?>