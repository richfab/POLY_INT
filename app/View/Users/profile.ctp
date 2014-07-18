<?php
    
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
    
    
<div class="row" id="profile_info">
    <div class="col col-sm-10">
        <h1><?= $user['User']['firstname'];?> <?= $user['User']['lastname'];?>
            <?php if(!$user['User']['email_is_hidden']):?>
            <a href="mailto:<?= $user['User']['email'];?>"><?= $this->Html->image('contact-email.png',array('class' => 'contact-logo', 'title' => 'Email', 'data-toggle' => 'tooltip'));?></a>
            <?php else:?>
                <?= $this->Html->image('contact-email.png',array('class' => 'contact-logo disabled', 'title' => 'Email', 'data-toggle' => 'tooltip'));?>
            <?php endif;?>
            <?php if($user['User']['linkedin']):?>
            <a href="<?= $user['User']['linkedin'];?>" target="_blank"><?= $this->Html->image('contact-linkedin.png',array('class' => 'contact-logo', 'title' => 'Profil LinkedIn', 'data-toggle' => 'tooltip'));?></a>
            <?php else:?>
                <?= $this->Html->image('contact-linkedin.png',array('class' => 'contact-logo disabled', 'title' => 'Profil LinkedIn', 'data-toggle' => 'tooltip'));?>
            <?php endif;?>
        </h1>
        <h4>
            <?= $this->Html->image('picto/'.$user['Department']['id'].'.png',array('class' => 'department_logo_profile', 'title' => $user['Department']['name'], 'data-toggle' => 'tooltip', 'data-placement' => 'bottom'));?> Polytech <?= $user['School']['name'];?>
        </h4>
        <?php if($user['User']['id'] == AuthComponent::user('id')) : ?>
        <p id="edit_profile_button">
            <?= $this->Html->link('<span class="edit-delete-label">Modifier</span>', array('action' => 'edit'),
                array('escape' => false,
                    'class' => 'glyphicon glyphicon-pencil edit-delete'
                    )); ?>
        </p>
    <?php endif; ?>
    </div>
    <div class="col col-sm-1">
        <!--si c'est mon profile-->
        <?php if($user['User']['id'] == AuthComponent::user('id')) : ?>
        <h1 data-toggle="modal" data-target="#upload_profilepic_modal" title="Changer ma photo" id="custom_avatar">
        <?php else:?>
            <h1>
        <?php endif;?>
                <!--si j'ai un avatar custom-->
        <?php if(!empty($user['User']['avatar'])) {
                echo $this->Image->resize($user['User']['avatar'], 128,128,array('alt' => 'avatar','onload' => "this.style.backgroundColor='#".$user['School']['color']."'", 'id' => 'avatar_profile'));
            } else {
                echo $this->Html->image('avatar.png', array('alt' => 'avatar','onload' => "this.style.backgroundColor='#".$user['School']['color']."'",'id' => 'avatar_profile'));
            }
        ?>
            </h1>
    </div>
</div>
<h3 style="display: inline-block">Expériences</h3>
    
    <?php if($user['User']['id'] == AuthComponent::user('id')) : ?>
<div class="well">
    <p><?= $this->Html->link("Ajouter une expérience", array('controller' => 'experiences', 'action' => 'info')); ?></p>
</div>
    <?php endif; ?>
<div id="wells-experience-profile">
    <?php foreach ($experiences as $experience): ?>
    <div class="well well-experience experience-info" id="<?= $experience['Experience']['id'];?>">
        
            <?php if($user['User']['id'] == AuthComponent::user('id')) : ?>
        
                <?= $this->Form->postLink('<span class="edit-delete-label">Supprimer</span>',
                    array('controller'=>'experiences', 'action' => 'delete', $experience['Experience']['id']),
                    array('confirm' => 'Es-tu sûr de vouloir supprimer cette expérience ?',
                        'escape' => false,
                        'class' => 'glyphicon glyphicon-remove close edit-delete'
                    ));
                ?>
                <?= $this->Html->link('<span class="edit-delete-label">Modifier</span>', array('controller'=>'experiences', 'action' => 'info', $experience['Experience']['id']),
                        array('escape' => false,
                            'class' => 'glyphicon glyphicon-pencil close edit-delete'
                        )); ?>
            <?php endif; ?>
        
            <?php echo $this->element('experience_info',array('experience'=>$experience)); ?>
        
            <?php if($user['User']['id'] == AuthComponent::user('id')) : ?>
        
        <div id="addRecommendation">
            <p>Partager un bon plan : 
                <?php foreach ($recommendationtypes as $recommendationtype) :?>
                <span class="glyphicon glyphicon-<?= $recommendationtype['Recommendationtype']['icon'];?> recommendationtype-icon recommendationtype-icon-selectable" recommendationtype_description="<?= $recommendationtype['Recommendationtype']['description'];?>" recommendationtype_id="<?= $recommendationtype['Recommendationtype']['id'];?>" data-toggle="tooltip" title="<?= $recommendationtype['Recommendationtype']['name']; ?>"></span>
                <?php endforeach;?>
            </p>
            
            <div class="addRecommendationForm">
                <div class="form-group">
                    <textarea rows=6 placeholder="" experience_id="<?= $experience['Experience']['id']; ?>" recommendationtype_id="" class="RecommendationContent form-control"></textarea>
                </div>
                <div class="form-group">    
                    <button type="button" onclick="add_recommendation($(this));" class="btn btn-blue">Partager</button>
                    <button type="button" onclick="closeAddRecommendationForm($(this));" class="btn btn-orange">Annuler</button>
                </div>
            </div>
        </div>
        
        
            <?php endif; ?>
        
        <div class="panel-group">
            <div class="panel panel-default panel-recommendations">
                <div class="panel-heading panel-heading-recommendations">
                    <h5 class="panel-title panel-title-recommendations">
                        <span style="width:20px" class="glyphicon glyphicon-comment"></span> Bons plans
                    </h5>
                </div>
                <div class="panel-collapse">
                    <div class="panel-body">
                            <?php foreach ($experience['Recommendation'] as $recommendation):?>
                        <div class="row">
                            <div class="col-sm-1" style="text-align:right;">
                                <p><span class="glyphicon glyphicon-<?= $recommendationtypes_list[$recommendation['recommendationtype_id']];?> recommendationtype-icon selected"></span></p>
                            </div>
                            <div class="col-sm-11">
                                    <?php if($user['User']['id'] == AuthComponent::user('id')) : ?>
                                        <?= $this->Form->postLink('<span class="edit-delete-label"></span>',
                                            array('controller'=>'recommendations', 'action' => 'delete', $recommendation['id']),
                                            array('confirm' => 'Es-tu sûr de vouloir supprimer ce bon plan ?',
                                                'escape' => false,
                                                'class' => 'glyphicon glyphicon-remove close edit-delete'
                                            ));
                                        ?>
                                    <?php endif;?>
                                    <?php echo $this->element('recommendation_text',array('recommendation'=>$recommendation)); ?>
                            </div>
                        </div>
                            <?php endforeach;?>
                            <?php if (!$experience['Recommendation']) :?>
                        <p>Aucun bon plan</p>
                            <?php endif;?>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if($user['User']['firstname'] == 'Facebook') : ?>
        <div class="panel-group">
            <div class="panel panel-default panel-photos">
                <div class="panel-heading panel-heading-photos">
                    <h5 class="panel-title panel-title-photos">
                        <span style="width:20px" class="glyphicon glyphicon-picture"></span> Photos 
                            <?php if($experience['Experience']['user_id'] == AuthComponent::user('id')) : ?>
                                <?php echo $this->element('fbalbum_import_button', array('experience' => $experience)); ?>
                            <?php endif; ?>
                    </h5>
                </div>
                <div class="panel-collapse">
                    <div class="panel-body photo_gallery" experience_id="<?= $experience['Experience']['id']; ?>">
                        
                    </div>
                </div>
            </div>
        </div>
        <?php endif;?>
        
    </div>
    <?php endforeach; ?>
</div>
    
<?php if($user['User']['id'] == AuthComponent::user('id')) : ?>
    
<!-- Modal profile pic-->
<div class="modal fade" id="upload_profilepic_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Modifier ma photo de profil</h4>
            </div>
            <div class="modal-body">
                <?php
                    echo $this->Form->create('User', array('type'=>'file'));
                    echo $this->Form->input('avatar_file', array('label' => false, 'type' => 'file'));
                ?>
                <p class="help-block">Au format jpg, png ou jpeg.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-orange" data-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </div>
        </div>
    </div>
</div>
<!-- End of Modal profile pic -->
    
<!-- Modal fb album -->
<div class="modal fade" id="import_fbalbum_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Importer un album Facebook</h4>
            </div>
            <div class="modal-body">
                <div id="loading_fb_album_list">
                    <p>Chargement des albums Facebook...</p>
                </div>
                <div id="fb_album_list">
                    
                </div>
                <div id="fbalbum_progress" class="progress" style="visibility: hidden">
                    <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                        0%
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End of Modal fb album -->
    
<?php endif; ?>

<?php
    echo $this->Html->script(array('jquery.blueimp-gallery.min.js')); // Inclut la librairie gallerie
?>
    
<script type="text/javascript">
    
    $( function() {
        
        //pour le chargement des galleries
        get_galleries('<?php echo $this->Html->url(array('controller' => 'photos', 'action' => 'get_photo_gallery'),true); ?>');
        
        //pour le changement d'etat des boutons de categorie de recommendation
        $( '.recommendationtype-icon-selectable' ).each(function() {
            $(this).on('click',function(){
                $( '.recommendationtype-icon-selectable' ).removeClass('selected');
                $(this).toggleClass('selected');
                $('.addRecommendationForm').slideUp('fast');
                $(this).parent().siblings('.addRecommendationForm').slideDown(function(){
                    $('.RecommendationContent').focus();
                });
                $('.RecommendationContent').attr('recommendationtype_id',$(this).attr('recommendationtype_id'));
                $('.RecommendationContent').attr('placeholder',$(this).attr('recommendationtype_description'));
            });
        });
        
        //pour les tooltips
        if (!Modernizr.touch) {
            $('.contact-logo').tooltip();
            $('.department_logo_profile').tooltip();
            $('.recommendationtype-icon').tooltip(); 
        }
        
        $('.recommendation-text').readmore();
        
        //DEBUT jump to moins la hauteur de la navbar
        function offsetAnchor() {
            //making sure that there is a valid anchor to offset from.
            if($(location.hash).length !== 0) {
                //on recupere le scrolltop
                var experienceTop = window.scrollY;
                //si on n'essaie pas de descendre jusqu'en bas du profile pour voir la derniere experience
                if($(document).height() > window.scrollY+$(window).height()){
                    experienceTop = experienceTop - 60;
                }
                //on remonte jusqu'en haut
                window.scrollTo(window.scrollX, 0);
                //on redescend avec une animation a l'experience
                window.setTimeout(function() {
                    $("html, body").animate({scrollTop:experienceTop},700, 'swing');
                }, 300);
            }
        }
        $(window).on("hashchange", function () {
            offsetAnchor();
        });
        window.setTimeout(function() {
            offsetAnchor();
        }, 1);
        //FIN jump to
        
    });
    
</script>