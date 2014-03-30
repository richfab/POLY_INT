<h1><?= $user['User']['firstname'];?> <?= $user['User']['lastname'];?></h1>
<p><?= $user['Department']['name'];?></p>
<p>Polytech <?= $user['School']['name'];?></p>
<p><?= $user['User']['email'];?></p>
<?php if($user['User']['id'] == AuthComponent::user('id')) : ?>
    <?= $this->Html->link("Modifier", array('action' => 'edit')); ?>
<?php endif; ?>

<?php foreach ($experiences as $experience): ?>
<div>
    <p>-------------------------------------------------------------------</p>
    <p>Lieu : <?= $experience['City']['name'];?>, <?= $experience['City']['Country']['name'];?></p>
    <p>Motif : <?= $experience['Motive']['name'];?></p>
    <?php if($experience['Experience']['description'] != ""): ?>
        <p>Description : <?= $experience['Experience']['description'];?></p>
    <?php else:?>
        <?php if($user['User']['id'] == AuthComponent::user('id')) : ?>
            <?= $this->Html->link("Ajouter une description", array('controller'=>'experiences', 'action' => 'info', $experience['Experience']['id'])); ?>
        <?php else: ?>
            <p>Pas de description</p>
        <?php endif; ?>
    <?php endif; ?>
    <p>Du : <?= $this->Time->format($experience['Experience']['dateStart'], '%B %e, %Y');?> Au : <?= $this->Time->format($experience['Experience']['dateEnd'], '%B %e, %Y')?></p>
    <?php if($experience['Experience']['note'] != 0): ?>
        <p>Avis : <?= $experience['Experience']['note'];?>/5</p>
        <?php if($experience['Experience']['comment'] != ""): ?>
            <p>"<?= $experience['Experience']['comment'];?>"</p>
        <?php endif; ?>
    <?php else:?>
        <?php if($user['User']['id'] == AuthComponent::user('id')) : ?>
            <?= $this->Html->link("Ajouter un avis", array('controller'=>'experiences', 'action' => 'info', $experience['Experience']['id'])); ?>
        <?php else: ?>
            <p>Pas encore d'avis</p>
        <?php endif; ?>
    <?php endif;?>
    <?php if($user['User']['id'] == AuthComponent::user('id')) : ?>
        <?= $this->Html->link("Modifier", array('controller'=>'experiences', 'action' => 'info', $experience['Experience']['id'])); ?>
        <?= $this->Form->postLink('Supprimer',
            array('controller'=>'experiences', 'action' => 'delete', $experience['Experience']['id']),
            array('confirm' => 'Es-tu sûr de vouloir supprimer cette expérience ?'));
        ?>
    <?php endif; ?>
</div>
<?php endforeach; ?>
<p>-------------------------------------------------------------------</p>
<?php if($user['User']['id'] == AuthComponent::user('id')) : ?>
<p><?= $this->Html->link("Ajouter une expérience", array('controller' => 'experiences', 'action' => 'info')); ?></p>
<?php endif; ?>