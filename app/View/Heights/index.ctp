<div class="" id="plane-contest"></div>
<div>
    <div class="well container">
        <div class="col-sm-12 text-center">
            <ul>
                <?php if(AuthComponent::user('id')):?>
                    <li>1. <?= $this->Html->link('Télécharge','/paperplanes/paperplane_id='.AuthComponent::user('school_id').'.pdf') ;?> et imprime le patron en papier de l'avion de ton école avec les instructions de pliage.</li>
                <?php else:?>
                    <li>1. Télécharge et imprime le patron de l'avion en papier de ton école avec les instructions de pliage.</li>
                <?php endif;?>
                <li>2. Grimpe en haut du plus grand monument de la ville</li>
                <li>3. Prends toi en photo et fais s'envoller l'avion</li>
                <li>4. Poste ta photo :</li>
            </ul>
            <!-- Button trigger modal -->
            <div class="text-center" style="margin:10px 0">
                <button class="btn btn-primary btn " data-toggle="modal" data-target="#myModal">Envoyer un avion</button>
            </div>
        </div>
    </div>
    <div class="col-sm-12 text-center">
        <div id="graph_contest" class="" style="padding-top:20px;"><?php echo $this->element('heights_graph');?></div>
    </div>



    <div class="col-sm-12 container">
        <div id="gallery"></div>
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
                    <div class="col col-sm-11 col-sm-offset-1 modal-instructions">
                        <?php if(AuthComponent::user('id')):?>
                            1. <?= $this->Html->link('Télécharge','/paperplanes/paperplane_id='.AuthComponent::user('school_id').'.pdf') ;?> et imprime le patron en papier de l'avion de ton école avec les instructions de pliage
                        <?php else:?>
                            1. Télécharge et imprime le patron de l'avion en papier de ton école avec les instructions de pliage
                        <?php endif;?>
                    </div>
                    <div class="col col-sm-11 col-sm-offset-1 modal-instructions">
                        2. Grimpe en haut du plus grand monument de la ville
                    </div>
                    <div class="col col-sm-11 col-sm-offset-1 modal-instructions">
                        3. Prends toi en photo et fais s'envoller l'avion
                    </div>
                    <div class="col col-sm-11 col-sm-offset-1 modal-instructions">
                        4. Poste ta photo :
                    </div>
                    <?php
                        if(AuthComponent::user('id')):?>
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
                                echo $this->Form->input('photo_file',array('type'=>'file','label'=>'<span class="glyphicon glyphicon-camera"></span>',"afterInput"=>"<span class='help-block'>Photo de toi avec l'avion en papier en haut du monument</span>"));
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

<script src="../js/jquery.blueimp-gallery.min.js"></script>
<script src="../js/bootstrap-image-gallery.min.js"></script>