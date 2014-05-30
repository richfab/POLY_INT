<?php

App::uses('MediasController', 'Media.Controller');
App::uses('AppModel', 'Model');

class Post extends AppModel{
    public $actsAs = array('Media.Media' => array(
        'extensions' => array('jpg','png','pdf'),
    ));
}
class Page extends AppModel{}
class TestmediasController extends MediasController {

    public $uses = array('Media.Media');

    public function canUploadMedias($ref, $ref_id){
        if($ref_id == '2'){
            return false;
        }
        return true;
    }

}

function test_move_uploaded_file($filename, $destination){
    return copy($filename, $destination);
}


class PluginMediaTest extends CakeTestSuite {
    public static function suite() {
        $suite = new CakeTestSuite('Plugin Media');
        $suite->addTestDirectoryRecursive(dirname(__FILE__));
        return $suite;
    }
}
