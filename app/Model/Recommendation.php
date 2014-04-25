<?php
App::uses('AppModel', 'Model');
/**
 * Recommendation Model
 *
 * @property Experience $Experience
 * @property Recommendationtype $Recommendationtype
 */
class Recommendation extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'recommendationtype_id';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

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
		),
		'Recommendationtype' => array(
			'className' => 'Recommendationtype',
			'foreignKey' => 'recommendationtype_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
