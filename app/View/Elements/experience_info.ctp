<div><?= $experience['Motive']['name'];?> à 
    <?php if($experience['Motive']['name'] !== 'Voyage'): ?>
        <strong><?= $experience['Experience']['establishment']; ?></strong> - 
    <?php endif; ?>
<?= $experience['City']['name'];?>, <?= $countries[$experience['City']['country_id']];?></div>
<?php if($experience['Motive']['name'] !== 'Voyage'): ?>
    <?php if($experience['Experience']['description'] != ""): ?>
<div><?= nl2br($experience['Experience']['description']);?></div>
    <?php else:?>
        <?php if($experience['Experience']['user_id'] == AuthComponent::user('id') && $this->params['action'] === 'profile') : ?>
        <div><?= $this->Html->link("Ajouter une description", array('controller'=>'experiences', 'action' => 'info', $experience['Experience']['id'])); ?></div>
        <?php else: ?>
            <div>Pas de description</div>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>
<div>Du <?= $this->Time->format($experience['Experience']['dateStart'], '%e %B %Y');?> au <?= $this->Time->format($experience['Experience']['dateEnd'], '%e %B %Y')?></div>
<?php if($experience['Experience']['comment'] != ""): ?>
    <div>"<?= nl2br($experience['Experience']['comment']);?>"</div>
<?php else:?>
    <?php if($experience['Experience']['user_id'] == AuthComponent::user('id') && $this->params['action'] === 'profile') : ?>
    <div><?= $this->Html->link("Ajouter un avis", array('controller'=>'experiences', 'action' => 'info', $experience['Experience']['id'])); ?></div>
    <?php else: ?>
        <!--<div>Pas encore d'avis</div>-->
    <?php endif; ?>
<?php endif;?>