<h1><?= $user['User']['firstname'];?> <?= $user['User']['lastname'];?></h1>
<p><span class="glyphicon glyphicon-book"></span> Polytech <?= $user['School']['name'];?> - <?= $user['Department']['name'];?></p>
<p><span class="glyphicon glyphicon-envelope"></span> <?= $user['User']['email'];?></p>
<?php if($user['User']['id'] == AuthComponent::user('id')) : ?>
    <p><?= $this->Html->link('<span class="edit-delete-label">Modifier</span>', array('action' => 'edit'),
        array('escape' => false,
            'class' => 'glyphicon glyphicon-pencil edit-delete'
            )); ?></p>
    <?php endif; ?>
    <h3 style="display: inline-block">Expériences</h3>
    <?php if($user['User']['id'] == AuthComponent::user('id')) : ?>
        <p style="text-align:right;display: inline-block"><?= $this->Html->link("ajouter", array('controller' => 'experiences', 'action' => 'info')); ?></p>
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
        
    </div>
<?php endforeach; ?>
<?php if(empty($experiences)): ?>
    <div class="well">
        <p><?= $this->Html->link("Ajoute ta première expérience", array('controller' => 'experiences', 'action' => 'info')); ?></p>
    </div>
<?php endif; ?>
