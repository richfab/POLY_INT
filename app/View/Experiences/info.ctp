<h2><?= __('Expérience');?></h2>
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
echo $this->Form->input('dateStart',array('label'=>__('Du').' *','dateFormat' => 'DMY','wrapInput'=>'col col-sm-6','style'=>'width:initial;display:inline-block'));
echo $this->Form->input('dateEnd',array('label'=>__('Au').' *','dateFormat' => 'DMY','wrapInput'=>'col col-sm-6','style'=>'width:initial;display:inline-block'));
//si c'est une modification d'expérience, on renseigne le lieu et l'établissement
if(!empty($this->data)&&!empty($countries)){
    $input_value = $this->data['City']['name'].', '.$countries[$this->data['City']['country_id']];
    $country_id = $this->data['City']['country_id'];
    $establishment = $this->data['Experience']['establishment'];
}
else{
    $input_value = '';
    $country_id = '';
    $establishment = '';
}
?>
<?php echo $this->Form->input('Motive.id');
echo $this->Form->input('motive_id',array('label'=>__('Motif').' *'));
echo $this->Form->input('establishmentinput',array('label'=>__('Établissement').' *','placeholder'=>__("Nom de l'entreprise ou de l'université"), 'value' => $establishment));
echo $this->Form->input('google_placeid',array('label'=>'google_placeid', 'type' => 'hidden'));
echo $this->Form->input('establishment',array('label'=>'establishment', 'type' => 'hidden'));
?>
<div id="ExperienceInputDiv">
    <?php echo $this->Form->input('input',array('label'=>__('Ville').' *', 'value'=>$input_value, 'location-types'=>'(cities)', 'type' => 'text'));?>
</div>
<?php 
echo $this->Form->input('City.name',array('label'=>'city_name','type'=>'hidden'));
echo $this->Form->input('City.lat', array('label'=>'latitude','type'=>'hidden'));
echo $this->Form->input('City.lon', array('label'=>'longitude','type'=>'hidden'));
echo $this->Form->input('City.Country.id', array('label'=>'id','type'=>'hidden','value'=>$country_id));
echo $this->Form->input('City.Country.name', array('label'=>'country_name','type'=>'hidden'));
echo $this->Form->input('description',array('label'=>__('Description'),'rows'=>3,'placeholder'=>__('Description de la mission')));
echo $this->Form->input('comment',array('label'=>__('Avis'),'type'=>'textarea','placeholder'=>__("Avis sur l'expérience")));
echo $this->Form->input('typenotification_id', array('value'=>'3','type'=>'hidden'));
?>
<div class="form-group">
    <div class="col col-md-9 col-md-offset-3">
        <?= $this->Html->link(__("Retour"), array('controller'=>'users', 'action' => 'profile'),
                              array('class' => 'btn btn-orange'
                                   )); ?>
        <?php echo $this->Form->button(__('Enregistrer'), array(
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
        var experienceEstablishmentDiv = $('#ExperienceEstablishmentinput').parent().parent();
        var experienceDescriptionDiv = $('#ExperienceDescription').parent().parent();
        var experienceInputDiv = $('#ExperienceInputDiv');

        $('#ExperienceMotiveId').change(checkIfVoyage);
        checkIfVoyage();

        function checkIfVoyage(){
            if($('#ExperienceMotiveId').find(":selected").text() === 'Voyage'){
                experienceEstablishmentDiv.remove();
                experienceDescriptionDiv.remove();
            }
            else{
                experienceMotiveIdDiv.after(experienceEstablishmentDiv);
                experienceInputDiv.after(experienceDescriptionDiv);
            }
        }
    });
</script>