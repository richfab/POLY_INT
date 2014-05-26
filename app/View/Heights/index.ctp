<div class="text-center" style="padding-top:10px;">
    <h2>Paper Plane Contest</h2>
    <div id="graph_contest" style="padding-top:40px;"><?php echo $this->element('heights_graph');?></div>
    <div id="gallery"></div>


<!-- Button trigger modal -->
<button class="btn btn-primary btn" data-toggle="modal" data-target="#myModal">
    Envoyer un avion
</button>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->Html->image('mini-logo.png', array('alt' => 'Logo','height'=>'24px')); ?> Paper Plane Battle</h4>
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
                        2. Monte en haut du plus grand monument de la ville
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