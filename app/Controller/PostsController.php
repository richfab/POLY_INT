<?php
App::uses('AppController', 'Controller');
/**
 * Posts Controller
 *
 */
class PostsController extends AppController {

	public $actsAs = array('Containable');


	public function index() {
		$posts = $this->Post->find('all', array(
			'fields' => array('Post.id', 'Post.title', 'Post.body','Post.created' ,'Thumb.file'),
			'contain' => array('Thumb')
			)
		);
		$this->set(compact('posts'));
	}

	public function view($id) {
		$post = $this->Post->findById($id);
		if(empty($post)) {
			throw new NotFoundException();
		}
		$this->set(compact('post'));

	}

}