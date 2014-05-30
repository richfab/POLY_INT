<?php
class MediaBehavior extends ModelBehavior{

	private $options = array(
		'path'    => 'img/uploads/%y/%m/%f',
		'extensions' => array('jpg','png'),
		'limit' => 0,
		'max_width' => 0,
		'max_height' => 0,
		'size' => 0, // in KB (Ko)
	);

	public function setup(Model $model, $config = array()){
		$model->medias = array_merge($this->options,$config);
		$model->hasMany['Media'] = array(
			'className'  => 'Media.Media',
			'foreignKey' => 'ref_id',
			'order'		 => 'Media.position ASC',
			'conditions' => 'ref = "'.$model->name.'"',
			'dependent'  => true
		);
		if($model->hasField('media_id')){
			$model->belongsTo['Thumb'] = array(
				'className'  => 'Media.Media',
				'foreignKey' => 'media_id',
				'conditions' => null,
				'counterCache'=> false
			);
		}
	}

	public function afterSave(Model $model, $created, $options = array()){
		if(!empty($model->data[$model->name]['thumb']['name'])){
			$file = $model->data[$model->name]['thumb'];

			// Current thumb
			$media_id = $model->field('media_id');
			if($media_id != 0){
				$model->Media->delete($media_id);
			}

			// Update thumb
			$model->Media->save(array(
				'ref_id' => $model->id,
				'ref'	 => $model->name,
				'file'   => $file
			));
			$model->saveField('media_id',$model->Media->id);
		}
	}

	public function afterFind(Model $model, $results, $primary = false){
		foreach($results as $k=>$v){
			// Thumbnail
			if(isset($v['Thumb']['file'])){
				$v[$model->name]['thumb'] = $v['Thumb']['file'];
			}
			if(!empty($v['Media'])){
				$v['Media'] = Set::Combine($v['Media'],'{n}.id','{n}');
			}
			if( !empty($v[$model->name]['media_id']) && isset($v['Media'][$v[$model->name]['media_id']]) ){
				$media_id = $v[$model->name]['media_id'];
				$v[$model->name]['thumb'] = $v['Media'][$media_id]['file'];
			}
			$results[$k] = $v;
		}
		return $results;
	}





}
