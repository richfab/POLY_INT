<?php
App::uses('AppController', 'Controller');
/**
 * Posts Controller
 *
 */
class PostsController extends AppController {

	public $paginate = array(
            'limit' => 1,
            'order' => array('created' => 'DESC')
    );

	public function index() {
		$data = $this->paginate('Post');
		$this->set('articles',$data);
	}
}