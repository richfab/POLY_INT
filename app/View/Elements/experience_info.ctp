<div><?= __($experience['Motive']['name']);?> <?= __('à');?> 
    <?php if($experience['Motive']['name'] !== 'Voyage'): ?>
        <strong><?= $experience['Experience']['establishment']; ?></strong> - 
    <?php endif; ?>
    <?= $experience['City']['name'];?>, <?= $countries[$experience['City']['country_id']];?>
</div>
<?php if($experience['Motive']['name'] !== 'Voyage'): ?>
    <?php if($experience['Experience']['description'] != ""): ?>
<div><?= nl2br($experience['Experience']['description']);?></div>
    <?php else:?>
        <?php if($experience['Experience']['user_id'] == AuthComponent::user('id') && $this->params['action'] === 'profile') : ?>
        <div><?= $this->Html->link(__("Ajouter une description"), array('controller'=>'experiences', 'action' => 'info', $experience['Experience']['id'])); ?></div>
        <?php else: ?>
            <div><?= __("Pas de description");?></div>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>
<div>
    <?php
        echo $this->element('friendly_date', array('date_start'=>$experience['Experience']['dateStart'], 'date_end'=>$experience['Experience']['dateEnd']));
    ?>
    <span class="small">
        (<?= $this->Time->format($experience['Experience']['dateStart'], '%e %B %Y');?> - <?= $this->Time->format($experience['Experience']['dateEnd'], '%e %B %Y')?>)
    </span>
    
</div>
<?php if($experience['Experience']['comment'] != ""): ?>
    <div>"<?= nl2br($experience['Experience']['comment']);?>"</div>
<?php else:?>
    <?php if($experience['Experience']['user_id'] == AuthComponent::user('id') && $this->params['action'] === 'profile') : ?>
        <div><?= $this->Html->link(__("Ajouter un avis"), array('controller'=>'experiences', 'action' => 'info', $experience['Experience']['id'])); ?></div>
    <?php endif; ?>
<?php endif;?>