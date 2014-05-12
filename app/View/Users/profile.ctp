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
        <p class="help-block"><span class="glyphicon glyphicon-book"></span> Polytech <?= $user['School']['name'];?> &middot; <?= $user['Department']['name'];?></p>
        <p>
            
        </p>
<?php if($user['User']['id'] == AuthComponent::user('id')) : ?>
        <p><?= $this->Html->link('<span class="edit-delete-label">Modifier</span>', array('action' => 'edit'),
        array('escape' => false,
            'class' => 'glyphicon glyphicon-pencil edit-delete'
            )); ?></p>
    <?php endif; ?>
    </div>
    <div class="col col-sm-1">
        <h1>
            <?= $this->Html->image('avatar.png', array('alt' => 'avatar','onload' => "this.style.backgroundColor='#".$user['School']['color']."'",'id' => 'avatar_profile'));?>
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
                
        <div class="panel-group" id="accordion">
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
            
            
    </div>
    <?php endforeach; ?>
</div>

<script type="text/javascript">
    
    $( function() {
        
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
            $('.recommendationtype-icon').tooltip(); 
        }
        
        $('.recommendation-text').readmore();
        
        //DEBUT jump to moins la hauteur de la navbar
        function offsetAnchor() {
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
        $(window).on("hashchange", function () {
            offsetAnchor();
        });
        window.setTimeout(function() {
            offsetAnchor();
        }, 1);
        //FIN jump to
        
    });
    
</script>