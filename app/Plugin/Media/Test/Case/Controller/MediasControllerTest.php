<?php
class MediasControllerTest extends ControllerTestCase {

    public $fixtures = array('plugin.media.post', 'plugin.media.media');

    public function testUpload() {
        $Medias = $this->generate('Medias', array(
            'methods' => array('canUploadMedias'),
            'models' => array(
                'Media.Media' => array('move_uploaded_file')
            )
        ));
        $Medias->expects($this->any())->method('canUploadMedias')->will($this->returnValue(true));
        $Medias->Media->expects($this->once())->method('move_uploaded_file')->will($this->returnCallback('test_move_uploaded_file'));
        $this->image = ROOT . DS . APP_DIR . DS . 'Plugin' . DS . 'Media' . DS . 'Test' . DS . 'testHelper.png';
        $_FILES = array('file' => array('name' => 'testHelper.png','type' => 'image/png','tmp_name' => $this->image,'error' => (int) 0,'size' => (int) 52085));
        $return = $this->testAction('/media/testmedias/upload/Post/1');

        $media = $Medias->Media->find('first', array(
            'conditions' => array('file LIKE' => "%testHelper.png%")
        ));
        $this->assertEquals('Post', $media['Media']['ref']);
        $this->assertEquals(1, $media['Media']['ref_id']);
        $this->assertEquals(true, file_exists(WWW_ROOT . trim($media['Media']['file'], '/')));
        $Medias->Media->delete($media['Media']['id']);
    }

    public function testUploadWrongFileType() {
        $this->image = ROOT . DS . APP_DIR . DS . 'Plugin' . DS . 'Media' . DS . 'Test' . DS . 'testHelper.png';
        $_FILES = array('file' => array('name' => 'testHelper.csv','type' => 'image/png','tmp_name' => $this->image,'error' => (int) 0,'size' => (int) 52085));
        $return = $this->testAction('/media/testmedias/upload/Post/1');
        $this->assertEquals(false, $return);
    }

    public function testDeleteImage() {
        $Medias = $this->generate('Testmedias');
        $vars = $this->testAction('/media/testmedias/delete/2');
        $this->assertEquals(2, $Medias->Media->find('count'));
    }

    public function testListing() {
        $Medias = $this->generate('Testmedias');
        $this->testAction('/media/testmedias/index/Post/1');
        $this->assertEquals(2, count($this->vars['medias']));
        $this->assertEquals(1, count($this->vars['thumbID']));
    }

    public function testOrder() {
        $Medias = $this->generate('Testmedias');
        $data = array(
            'data' => array(
                'Media' => array(1 => 0, 2 => 1)
            )
        );
        $this->testAction('/media/testmedias/order', array('data' => $data));
        $medias = $Medias->Media->find('list', array('fields' => array('id','position')));
        $this->assertEquals(0,$medias[1]);
        $this->assertEquals(1,$medias[2]);
    }

    public function testThumb() {
        $Medias = $this->generate('Testmedias');
        $this->testAction('/media/testmedias/thumb/2');
        $Medias->Post->id = 1;
        $this->assertEquals(2, $Medias->Post->field('media_id'));
    }

    public function testNoFoundDelete() {
        $this->expectException('NotFoundException');
        $this->testAction('/media/testmedias/delete/5');
    }

    public function testForbiddenIndex() {
        $this->expectException('ForbiddenException');
        $this->testAction('/media/testmedias/index/Post/2');
    }

    public function testForbiddenUpload() {
        $this->expectException('ForbiddenException');
        $this->testAction('/media/testmedias/upload/Post/2');
    }

    public function testForbiddenDelete() {
        $this->expectException('ForbiddenException');
        $this->testAction('/media/testmedias/delete/3');
    }

    public function testForbiddenThumb() {
        $this->expectException('ForbiddenException');
        $this->testAction('/media/testmedias/thumb/3');
    }

    public function testForbiddenOrder() {
        $this->expectException('ForbiddenException');
        $data = array(
            'data' => array(
                'Media' => array(3 => 0)
            )
        );
        $this->testAction('/media/testmedias/order', array('data' => $data));
    }

}