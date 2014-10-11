<div id="body-contest">

    <div class="container">

        <div class="" id="plane-contest">
            <h2 class="page-title"><b>Selfies</b> <?= __('autour du monde');?></h2>
        </div>


        <div id="heights_gallery_instructions">

            <div id="gallery"></div>

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
echo $this->Form->input('place',array('label'=>'<span class="glyphicon glyphicon-globe"></span>','rows' => 2,"afterInput"=>"<span class='help-block'>Détails sur le monument (nom, lieu et hauteur)</span>"));
else:
echo $this->Html->link("Connecte-toi", array('controller'=>'users', 'action' => 'login'),array("style"=>"display:inline-block")).' ou '.$this->Html->link("inscris-toi", array('controller'=>'users', 'action' => 'signup'),array("style"=>"display:inline-block"))." pour participer";
endif;
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                <?php if(AuthComponent::user('id')):?>
                <button type="submit" class="btn btn-primary">Envoyer</button>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>
<!-- Fin de Modal -->

<?php
echo $this->Html->script(array('jquery.blueimp-gallery.min.js')); // Inclut la librairie gallerie
?>