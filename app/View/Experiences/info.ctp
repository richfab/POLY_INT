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
    echo $this->Form->input('dateStart',array('label'=>'Du *','dateFormat' => 'DMY','wrapInput'=>'col col-sm-6','style'=>'width:initial;display:inline-block'));
    echo $this->Form->input('dateEnd',array('label'=>'Au *','dateFormat' => 'DMY','wrapInput'=>'col col-sm-6','style'=>'width:initial;display:inline-block'));
    //si c'est une modification d'expérience, on renseigne le lieu
    if(!empty($this->data)){
        $input_value = $this->data['City']['name'].', '.$countries[$this->data['City']['country_id']];
    }
    else{
        $input_value = '';
    }?>
<div id="ExperienceInputDiv">
        <?php echo $this->Form->input('input',array('label'=>'Ville *', 'value'=>$input_value, 'location-types'=>'(cities)','afterInput'=>'<span class="help-block">De préférence une grande ville pour une meilleure visibilité</span>'));?>
</div>
    <?php echo $this->Form->input('Motive.id');
    echo $this->Form->input('motive_id',array('label'=>"Motif *"));
    echo $this->Form->input('establishment',array('label'=>'Établissement *','placeholder'=>"Nom de l'entreprise ou de l'université"));
    echo $this->Form->input('description',array('label'=>'Description','rows'=>3,'placeholder'=>'Description de la mission'));
    echo $this->Form->input('City.name',array('label'=>'city_name','type'=>'hidden'));
    echo $this->Form->input('City.lat', array('label'=>'latitude','type'=>'hidden'));
    echo $this->Form->input('City.lon', array('label'=>'longitude','type'=>'hidden'));
    echo $this->Form->input('City.Country.id', array('label'=>'id','type'=>'hidden'));
    echo $this->Form->input('City.Country.name', array('label'=>'country_name','type'=>'hidden'));
    echo $this->Form->input('comment',array('label'=>'Avis','type'=>'textarea','placeholder'=>"Avis sur l'expérience"));
    echo $this->Form->input('Typenotification.id');
    echo $this->Form->input('typenotification_id',array('label'=>"Notifications *","afterInput"=>"<span class='help-block'><strong>Fonctionnalité à venir : </strong>Recevoir un email si quelqu'un est au même en endroit en même temps</span>"));?>
<div class="form-group">
    <div class="col col-md-9 col-md-offset-3">
        <?= $this->Html->link("Retour", array('controller'=>'users', 'action' => 'profile'),
                array('class' => 'btn btn-orange'
        )); ?>
        <?php echo $this->Form->button('Enregistrer', array(
                'class' => 'btn btn-blue',
                'id' => 'validatePlaceButton'
        ));?>
    </div>
</div>
    
<?php echo $this->Form->end(); ?>
    
<script type="text/javascript">
    $( function() {
        
        //cette fonction permet de supprimer les champs description et etablissement si le motif "Voyage" est sélectionné
        var experienceMotiveIdDiv = $('#ExperienceMotiveId').parent().parent();
        var experienceEstablishmentDiv = $('#ExperienceEstablishment').parent().parent();
        var experienceDescriptionDiv = $('#ExperienceDescription').parent().parent();
        
        $('#ExperienceMotiveId').change(checkIfVoyage);
        checkIfVoyage();
        
        function checkIfVoyage(){
            if($('#ExperienceMotiveId').find(":selected").text() === 'Voyage'){
                experienceEstablishmentDiv.remove();
                experienceDescriptionDiv.remove();
            }
            else{
                experienceMotiveIdDiv.after(experienceEstablishmentDiv);
                experienceEstablishmentDiv.after(experienceDescriptionDiv);
            }
        }
    });
</script>