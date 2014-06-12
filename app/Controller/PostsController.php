<?php
/**
 * Posts Controller
 *
 * @property Post $Post
 * @property PaginatorComponent $Paginator
 */
App::uses('AppController', 'Controller');

/**
 * Posts Controller
 *
 * This class defines all actions relative to Posts
 *
 * @package		app.Controller
 */
class PostsController extends AppController {
    
    /**
    * Pagination options
    *
    * @var array
    */
    public $paginate = array(
        'limit' => 2,
        'order' => array('created' => 'DESC')
    );

    /**
    * This method allows user to see posts
    *
    * @return void
    */
    public function index() {
            $this->Paginator->settings = $this->paginate;
            $this->set('articles', $this->Paginator->paginate());
    }
}