<?php
App::uses('AppModel', 'Model');
/**
 * Recommendationtype Model
 *
 * @property Recommendation $Recommendation
 */
class Recommendationtype extends AppModel {

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Recommendation' => array(
			'className' => 'Recommendation',
			'foreignKey' => 'recommendationtype_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
