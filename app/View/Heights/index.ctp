<?php
    
echo $this->element('heights_graph');?>

<div id="gallery"></div>

<!-- Button trigger modal -->
<button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">
    Envoyer un avion
</button>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Paper Plane Battle</h4>
            </div>
            <div class="modal-body">
                    <?php
                        if(AuthComponent::user('id')){
                            echo $this->Form->create('Height', array(
                                'inputDefaults' => array(
                                        'div' => 'form-group',
                                        'label' => array(
                                                'class' => 'col col-sm-5 control-label'
                                        ),
                                        'wrapInput' => 'col col-sm-7',
                                        'class' => 'form-control'
                                ),
                                'class' => 'well form-horizontal'
                            ));
                                echo $this->Form->input('photo_file',array('type'=>'file','label'=>'La photo (selfie autorisée)'));
                                echo $this->Form->input('place',array('label'=>'Le monument et le lieu','rows' => 2));
                        }else{
                            echo $this->Html->link("Connecte-toi", array('controller'=>'users', 'action' => 'login'),array("style"=>"display:inline-block")).' ou '.$this->Html->link("inscris-toi", array('controller'=>'users', 'action' => 'signup'),array("style"=>"display:inline-block"))." pour participer";
                        }
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