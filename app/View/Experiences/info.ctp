<h2>Expérience</h2>
<?php 
    echo $this->Form->create('Experience', array(
        'inputDefaults' => array(
                'div' => 'form-group',
                'label' => array(
                        'class' => 'col col-sm-3 control-label'
                ),
                'wrapInput' => 'col col-sm-6',
                'class' => 'form-control'
        ),
        'class' => 'well form-horizontal'
    ));?>
    <?php
    echo $this->Form->input('dateStart',array('label'=>'Du *','dateFormat' => 'DMY','wrapInput'=>'col col-sm-4','style'=>'width:initial;display:inline-block'));
    echo $this->Form->input('dateEnd',array('label'=>'Au *','dateFormat' => 'DMY','wrapInput'=>'col col-sm-4','style'=>'width:initial;display:inline-block'));
    //si c'est une modification d'expérience, on renseigne le lieu
    if(!empty($this->data)){
        $input_value = $this->data['City']['name'].', '.$this->data['City']['Country']['name'];
    }
    else{
        $input_value = '';
    }?>
<?php
    echo $this->Form->input('input',array('label'=>'Ville *', 'value'=>$input_value, 'location-types'=>'(cities)'));
    echo $this->Form->input('Motive.id');
    echo $this->Form->input('motive_id',array('label'=>"Motif *"));
    echo $this->Form->input('establishment',array('label'=>'Établissement'));
    echo $this->Form->input('description',array('label'=>'Description'));
    echo $this->Form->input('City.name',array('label'=>'city_name','type'=>'hidden'));
    echo $this->Form->input('City.lat', array('label'=>'latitude','type'=>'hidden'));
    echo $this->Form->input('City.lon', array('label'=>'longitude','type'=>'hidden'));
    echo $this->Form->input('City.Country.id', array('label'=>'id','type'=>'hidden'));
    echo $this->Form->input('City.Country.name', array('label'=>'country_name','type'=>'hidden'));
//    echo $this->Form->input('City.name',array('label'=>'city_name'));
//    echo $this->Form->input('City.lat', array('label'=>'latitude'));
//    echo $this->Form->input('City.lon', array('label'=>'longitude'));
//    echo $this->Form->input('City.Country.id', array('label'=>'id','type'=>'text'));
//    echo $this->Form->input('City.Country.name', array('label'=>'country_name'));
    echo $this->Form->input('comment',array('label'=>'Avis','type'=>'textarea'));
//    echo $this->Form->input('notify',array('label'=>"Envoyez-moi un email si quelqu'un est dans cette ville en même temps que moi",'class' => false));?>
<div class="form-group">
    <div class="col col-md-9 col-md-offset-3">
        <?= $this->Html->link("Retour", array('controller'=>'users', 'action' => 'profile'),
                array('class' => 'btn btn-orange'
        )); ?>
        <?php echo $this->Form->button('Enregistrer', array(
                'class' => 'btn btn-blue'
        ));?>
    </div>
</div>

<?php echo $this->Form->end(); ?>