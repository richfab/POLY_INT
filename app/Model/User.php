<?php
App::uses('AuthComponent', 'Controller/Component');
    
class User extends AppModel {
    
    public $belongsTo = array('Department','School');
    public $hasMany = array(
        'Experience' => array(
            'className' => 'Experience',
            'order' => 'Experience.dateEnd DESC'
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
        ),
        'facebook_url' => array(
            'rule' => 'url',
            'message' => "L'adresse de votre profil Facebook doit être du type http://facebook.com/nom_utilisateur"
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
	    return true;
	}
}
?>