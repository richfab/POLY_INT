<h1>Ajouter une expérience</h1>
<?php echo $this->Form->create('Experience'); ?>
<?php 
    echo $this->Form->input('dateStart',array('label'=>'Date de début','type'=>'date','timeFormat'=>'24','dateFormat' => 'DMY'));
    echo $this->Form->input('dateEnd',array('label'=>'Date de fin','type'=>'date','timeFormat'=>'24','dateFormat' => 'DMY'));
    //si c'est une modification d'expérience, on renseigne le lieu
    if(!empty($this->data)){
        $input_value = $this->data['City']['name'].', '.$this->data['City']['Country']['name'];
    }
    else{
        $input_value = '';
    }
    echo $this->Form->input('input',array('label'=>'Ville', 'value'=>$input_value));
    echo $this->Form->input('Motive.id');
    echo $this->Form->input('motive_id',array('label'=>"Motif"));
    echo $this->Form->input('description',array('label'=>'Description'));
    echo $this->Form->input('City.name',array('label'=>'city_name','type'=>'hidden'));
    echo $this->Form->input('City.lat', array('label'=>'latitude','type'=>'hidden'));
    echo $this->Form->input('City.lon', array('label'=>'longitude','type'=>'hidden'));
    echo $this->Form->input('City.Country.code', array('label'=>'code','type'=>'hidden'));
    echo $this->Form->input('City.Country.name', array('label'=>'country_name','type'=>'hidden'));
//    echo $this->Form->input('City.name',array('label'=>'city_name'));
//    echo $this->Form->input('City.lat', array('label'=>'latitude'));
//    echo $this->Form->input('City.lon', array('label'=>'longitude'));
//    echo $this->Form->input('City.Country.code', array('label'=>'code'));
//    echo $this->Form->input('City.Country.name', array('label'=>'country_name'));
?>
<?php echo $this->Form->end(__('Suivant'));