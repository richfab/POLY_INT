<?php
App::uses('AppController', 'Controller');
/**
 * Posts Controller
 *
 */
class PostsController extends AppController {

	public $paginate = array(
            'limit' => 3,
            'order' => array('created' => 'DESC')
    );

	public function index() {
		$this->Paginator->settings = $this->paginate;
		$this->set('articles', $this->Paginator->paginate());
	}
}