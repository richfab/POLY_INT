<?php
App::uses('AppController','Controller');
class MediasController extends AppController{

    public $order = array('Media.position ASC');

    public function isAuthorized($user = null){
        return true;
    }

    public function canUploadMedias($ref, $ref_id){
        if(method_exists('AppController', 'canUploadMedias')){
            return parent::canUploadMedias($ref, $ref_id);
        }else{
            return false;
        }
    }

    public function beforeFilter(){
        parent::beforeFilter();
        $this->layout = 'uploader';
        if(in_array('Security', $this->components)){
            $this->Security->unlockedActions = array('upload', 'order','index','delete','thumb');
        }
    }

    /**
    * Liste les médias
    **/
    public function index($ref,$ref_id){
        if(!$this->canUploadMedias($ref, $ref_id)){
            throw new ForbiddenException();
        }
        $this->loadModel($ref);
        $this->set(compact('ref', 'ref_id'));
        if(!in_array('Media', $this->$ref->Behaviors->loaded())){
            return $this->render('nobehavior');
        }
        $id = isset($this->request->query['id']) ? $this->request->query['id'] : false;
        $medias = $this->Media->find('all',array(
            'conditions' => array('ref_id' => $ref_id,'ref' => $ref)
        ));

        $thumbID = false;
        if($this->$ref->hasField('media_id')){
            $this->$ref->id = $ref_id;
            $thumbID = $this->$ref->field('media_id');
        }
        $extensions = $this->$ref->medias['extensions'];
        $editor = isset($this->request->params['named']['editor']) ? $this->request->params['named']['editor'] : false;
        $this->set(compact('id', 'medias', 'thumbID', 'editor', 'extensions'));
    }

    /**
    * Upload (Ajax)
    **/
    public function upload($ref,$ref_id){
        $this->autoRender = false;
        if(!$this->canUploadMedias($ref, $ref_id)){
            throw new ForbiddenException();
        }
        $media = $this->Media->save(array(
            'ref'    => $ref,
            'ref_id' => $ref_id,
            'file'   => $_FILES['file']
        ));
        if(!$media){
            echo json_encode(array('error' => $this->Media->error));
            return false;
        }
        $this->loadModel($ref);
        $media = $this->Media->read();
        $media = $media['Media'];
        $thumbID = $this->$ref->hasField('media_id');
        $editor = isset($this->request->params['named']['editor']) ? $this->request->params['named']['editor'] : false;
        $id = isset($this->request->query['id']) ? $this->request->query['id'] : false;
        $this->set(compact('media', 'thumbID', 'editor', 'id'));
        $this->layout = 'json';
        $this->render('media');
        return true;
    }

    /**
    * Suppression (Ajax)
    **/
    public function delete($id){
        $this->autoRender = false;
        $media = $this->Media->findById($id, array('ref','ref_id'));
        if(empty($media)){
            throw new NotFoundException();
        }
        if(!$this->canUploadMedias($media['Media']['ref'], $media['Media']['ref_id'])){
            throw new ForbiddenException();
        }
        $this->Media->delete($id);
        return true;
    }

    /**
    * Met l'image à la une
    **/
    public function thumb($id){
        $this->Media->id = $id;
        $media = $this->Media->findById($id, array('ref','ref_id'));
        if(empty($media)){
            throw new NotFoundException();
        }
        if(!$this->canUploadMedias($media['Media']['ref'], $media['Media']['ref_id'])){
            throw new ForbiddenException();
        }
        $ref = $media['Media']['ref'];
        $ref_id = $media['Media']['ref_id'];
        $this->loadModel($ref);
        $this->$ref->id = $ref_id;
        $this->$ref->saveField('media_id',$id);
        $this->redirect(array('action' => 'index', $ref, $ref_id));
    }

    public function order(){
        if(!empty($this->request->data['Media'])){
            $id = key($this->request->data['Media']);
            $media = $this->Media->findById($id, array('ref','ref_id'));
            if(!$this->canUploadMedias($media['Media']['ref'], $media['Media']['ref_id'])){
                throw new ForbiddenException();
            }
            foreach($this->request->data['Media'] as $k=>$v){
                $this->Media->id = $k;
                $this->Media->saveField('position',$v);
            }
        }
        return false;
    }


}
