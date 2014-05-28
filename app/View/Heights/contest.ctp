<?php
    
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */?>
     
<div class="" id="plane-contest"></div>
    
<div class="container">
    <div id="cloud1"></div>  
    <div id="cloud2"></div>
    <div class="col-sm-12 text-center" style="margin-top:-100px;">
        <div id="graph_contest" class="" style="padding-top:20px;min-height: 358px;"><?php echo $this->element('heights_graph');?></div>
    </div>
        
    <div class="row" id="heights_gallery_instructions">
        <div class="col-md-4">
            <div class="well">
                <h2 id="how_to_participate">Comment participer ?</h2>
                    <?php if(AuthComponent::user('id')):?>
                <p><strong>1.</strong> <?= $this->Html->link('Télécharge','/img/heights-paperplanes/PaperPlaneContest-'.AuthComponent::user('school_id').'.pdf') ;?> et imprime le patron en papier de l'avion de ton école avec les instructions de pliage.</p>
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
                <p>&middot; Les écoles sont les unes contre les autres.</p>
                <p>&middot; Le concours est du <strong>1er juin au 31 août</strong> 2014.</p>
                <p>&middot; Le score est calculé en fonction de la <strong>hauteur des monuments</strong> de chaque photo.</p>
                <p>&middot; Il faut être <strong>à l’étranger</strong>.</p>
                <p>&middot; Les montagnes et les statues, ça compte.</p>
                <p>&middot; L’école gagnante sera élue <strong>l’école la plus internationale</strong> du réseau Polytech.</p>
            </div>
        </div>
            
        <div class="col-md-8">
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
                                echo $this->Form->input('photo_file',array('type'=>'file','label'=>'<span class="glyphicon glyphicon-camera"></span>',"afterInput"=>"<span class='help-block'>Photo de toi avec l'avion en papier devant ou en haut du monument (Max 3.4Mo)</span>"));
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