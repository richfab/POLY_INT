<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
    

<div class="row" id="profile_info">
    <div class="col col-sm-10">
        <h1><?= $user['User']['firstname'];?> <?= $user['User']['lastname'];?></h1>
        <p><span class="glyphicon glyphicon-book"></span> Polytech <?= $user['School']['name'];?> - <?= $user['Department']['name'];?></p>
        <p><span class="glyphicon glyphicon-envelope"></span> <?= $user['User']['email'];?></p>
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
        
<?php foreach ($experiences as $experience): ?>
    <div class="well">
    
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
        
        <div class="panel-group" id="accordion">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5 class="panel-title panel-title-recommendations">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $experience['Experience']['id']; ?>">
                            <span style="width:20px" class="glyphicon glyphicon-tags"></span> Bons plans
                        </a>
                    </h5>
                </div>
                <div id="collapse<?= $experience['Experience']['id']; ?>" class="panel-collapse collapse">
                    <div class="panel-body">
                        <?php if($user['User']['id'] == AuthComponent::user('id')) : ?>
                            <p><small>Recommandez des hébergements, des bars, des moyens de transports, etc :</small></p>
                            <?php foreach ($recommendationtypes as $recommendationtype) : ?>

                                <div class="input-group form-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-<?= $recommendationtype['Recommendationtype']['icon'];?>"></span></span>

                                <?php $recommendationIsEmpty=true;?>

                                <?php foreach ($experience['Recommendation'] as $recommendation):?>
                                    <?php if($recommendation['recommendationtype_id'] === $recommendationtype['Recommendationtype']['id']):?>
                                        <?php $recommendationIsEmpty=false;?>
                                        <input name="RecommandationContent" placeholder="<?= $recommendationtype['Recommendationtype']['name']; ?>" value="<?= $recommendation['content'];?>" recommendation_id="<?= $recommendation['id'];?>" experience_id="<?= $experience['Experience']['id']; ?>" recommendationtype_id="<?= $recommendationtype['Recommendationtype']['id'];?>" class="form-control recommendation-content"/>
                                    <?php endif;?>
                                <?php endforeach;?>

                                <?php if($recommendationIsEmpty):?>
                                    <input name="RecommandationContent" placeholder="<?= $recommendationtype['Recommendationtype']['name']; ?>" experience_id="<?= $experience['Experience']['id']; ?>" recommendationtype_id="<?= $recommendationtype['Recommendationtype']['id'];?>" class="form-control recommendation-content"/>
                                <?php endif;?>

                                </div>
                            <?php endforeach;?>

                            <button type="button" onclick="add_recommendations(this);" class="btn btn-default">Enregistrer</button>
                        <?php else :?>
                            <?php foreach ($experience['Recommendation'] as $recommendation):?>
                                <div class="row">
                                    <div class="col-sm-1" style="text-align:right;">
                                        <p><span class="glyphicon glyphicon-<?= $recommendationtypes_list[$recommendation['recommendationtype_id']];?> recommendationtype-icon selected"></span></p>
                                    </div>
                                    <div class="col-sm-11">
                                        <p><?= $recommendation['content']; ?></p>
                                    </div>
                                </div>
                            <?php endforeach;?>
                            <?php if (!$experience['Recommendation']) :?>
                                <p>Aucun bon plan</p>
                            <?php endif;?>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>
        

    </div>
<?php endforeach; ?>
