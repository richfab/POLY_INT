<p><?= $experience['Motive']['name'];?> à <strong><?= $experience['Experience']['establishment']; ?></strong> - <?= $experience['City']['name'];?>, <?= $experience['City']['Country']['name'];?></p>
<?php if($experience['Experience']['description'] != ""): ?>
    <p><?= $experience['Experience']['description'];?></p>
<?php else:?>
    <?php if($experience['Experience']['user_id'] == AuthComponent::user('id')) : ?>
    <p><?= $this->Html->link("Ajouter une description", array('controller'=>'experiences', 'action' => 'info', $experience['Experience']['id'])); ?></p>
    <?php else: ?>
        <p>Pas de description</p>
    <?php endif; ?>
<?php endif; ?>
<p>Du <?= $this->Time->format($experience['Experience']['dateStart'], '%e %B %Y');?> au <?= $this->Time->format($experience['Experience']['dateEnd'], '%e %B %Y')?></p>
<?php if($experience['Experience']['comment'] != ""): ?>
    <p>"<?= $experience['Experience']['comment'];?>"</p>
<?php else:?>
    <?php if($experience['Experience']['user_id'] == AuthComponent::user('id')) : ?>
    <p><?= $this->Html->link("Ajouter un avis", array('controller'=>'experiences', 'action' => 'info', $experience['Experience']['id'])); ?></p>
    <?php else: ?>
        <p>Pas encore d'avis</p>
    <?php endif; ?>
<?php endif;?>