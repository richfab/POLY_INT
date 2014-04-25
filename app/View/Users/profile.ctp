    

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
    
    <form class="form-inline recommendation-form">
        <input class="recommendation-experience_id" name="RecommandationExperience_id" type="hidden" value="<?= $experience['Experience']['id'];?>"/>
        <input name="RecommandationContent" class="form-control recommendation-content"/>
        <button type="button" onclick="add_recommendation(this);" class="btn btn-default">Enregistrer</button>
        <input class="recommendation-recommendationtype_id" name="RecommandationRecommandationtype_id" value="1"/>
    </form>
        
</div>
<?php endforeach; ?>