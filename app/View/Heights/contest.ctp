<div class="" id="plane-contest"></div>
    
<div id="body-contest">
    
    <div class="container"> 

        <div class="" id="plane-contest">
            <h2 class="page-title"><b>Selfies</b> <?= __('autour du monde');?></h2>
            <h4  class="page-title">– été 2015 –</h4>
        </div>
        
        <div class="col-sm-12 text-center">
            <div id="graph_contest" class="" style="padding-top:20px;min-height: 371px;"><?php echo $this->element('heights_graph');?></div>
        </div>
            
        <div class="row" id="heights_gallery_instructions">
            <div class="col-md-4">
                <div class="well">
                    <h2 id="how_to_participate">Comment participer ?</h2>
                    <?php if(AuthComponent::user('id')):?>
                    <p><strong>1.</strong> <?= $this->Html->link('Télécharge','/img/heights-paperplanes/PaperPlaneContest-'.AuthComponent::user('school_id').'.pdf') ;?> l'avion en papier de ton école avec les instructions de pliage (ou fabrique le tien).</p>
                            <?php else:?>
                    <p><strong>1.</strong> <?= $this->Html->link('Télécharge',array('controller' => 'users', 'action' => 'login')) ;?> et imprime le patron de l'avion en papier de ton école avec les instructions de pliage.</p>
                            <?php endif;?>
                    <p><strong>2.</strong> Grimpe en haut du plus grand monument de la ville ou prends la pose devant.</p>
                    <p><strong>3.</strong> Prends toi en photo et fais s'envoler l'avion !</p>
                    <!-- Button trigger modal -->
                    <div class="text-center" style="margin:10px 0">
                        <button class="btn btn-primary btn " data-toggle="modal" data-target="#myModal">Envoyer un avion</button>
                    </div>
                </div>
                    
                <div class="well">
                    <h2 id="rules">Les règles</h2>
                    <p>&middot; Les <strong>écoles Polytech</strong> sont les unes contre les autres.</p>
                    <p>&middot; Tu as du <strong>18 juin au 31 août</strong> 2015 pour faire gagner ton école.</p>
                    <p>&middot; Le score est calculé en fonction de la <strong>hauteur des monuments</strong> de chaque photo.</p>
                    <p>&middot; Il faut être <strong>à l’étranger</strong>.</p>
                    <p>&middot; L'<strong>avion en papier</strong> doit être visible sur la photo.</p>
                    <p>&middot; Maximum <strong>3 photos</strong> par personne, par ville visitée.</p>
                    <p>&middot; L’école gagnante sera élue <strong>l’école la plus internationale</strong> du réseau Polytech.</p>
                </div>
            </div>
                
            <div class="col-md-8">
                <div id="gallery"></div>
            </div>
                
        </div>
    </div>
</div>
    
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->Html->image('mini-logo.png', array('alt' => 'Logo','height'=>'24px')); ?> Paper Plane Contest</h4>
            </div>
            <div class="modal-body">
                    <?php
                        if(AuthComponent::user('id')):?>
                            <p>Cet avion comptera pour le score de <strong><?= $school_names[AuthComponent::user('school_id')] ;?></strong>.</p>
                            <?php
                            echo $this->Form->create('Height', array(
                                'inputDefaults' => array(
                                        'div' => 'form-group',
                                        'label' => array(
                                                'class' => 'col col-sm-1 control-label'
                                        ),
                                        'wrapInput' => 'col col-sm-11',
                                        'class' => 'form-control'
                                ),
                                'class' => 'well form-horizontal',
                                'type'=>'file'
                            ));
                                echo $this->Form->input('photo_file',array('type'=>'file','label'=>'<span class="glyphicon glyphicon-camera"></span>',"afterInput"=>"<span class='help-block'>Photo de toi <strong>avec l'avion en papier</strong> devant ou en haut du monument (Max 3.4Mo)</span>"));
                                echo $this->Form->input('place',array('label'=>'<span class="glyphicon glyphicon-globe"></span>',"afterInput"=>"<span class='help-block'>Nom du bâtiment</span>"));
                                echo $this->Form->input('input',array('label'=>'<span class="glyphicon glyphicon-map-marker"></span>',"afterInput"=>"<span class='help-block'>Ville où se trouve le bâtiment</span>", 'location-types'=>'(cities)', 'type' => 'text', 'id' => 'ExperienceInput'));
                                echo $this->Form->input('Height.height',array('label'=>'<span class="glyphicon glyphicon-send"></span>',"afterInput"=>"<span class='help-block'>Hauteur en mètres (sera vérifiée)</span>", 'type' => 'number'));
                                echo $this->Form->input('Height.city',array('label'=>'city_name','type'=>'hidden', 'id' => 'CityName'));
                                echo $this->Form->input('Height.country', array('label'=>'country_name','type'=>'hidden', 'id' => 'CityCountryName'));
                        else:
                            echo $this->Html->link("Connecte-toi", array('controller'=>'users', 'action' => 'login'),array("style"=>"display:inline-block")).' ou '.$this->Html->link("inscris-toi", array('controller'=>'users', 'action' => 'signup'),array("style"=>"display:inline-block"))." pour participer";
                        endif;
                    ?>
                    <input id="CityLat" type="hidden"></input><input id="CityLon" type="hidden"></input></input><input id="CityCountryId" type="hidden"></input>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                <?php if(AuthComponent::user('id')):?>
                <button type="submit" class="btn btn-primary" id="validatePlaceButton">Envoyer</button>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>
<!-- Fin de Modal -->
    
<?php
    echo $this->Html->script(array('jquery.blueimp-gallery.min.js')); // Inclut la librairie gallerie
    echo $this->Html->script(array('http://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=fr&libraries=places', 'places_autocomplete')); // Inclut la librairie google maps
?>