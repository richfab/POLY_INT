<?php if(AuthComponent::user('id')) : ?>
    <?php foreach ($experiences as $experience): ?>
    <div class="well">
        
        <p>Lieu : <?= $experience['City']['name'];?>, <?= $experience['City']['Country']['name'];?></p>
        <p>Motif : <?= $experience['Motive']['name'];?></p>
        <?php if($experience['Experience']['description'] != ""): ?>
            <p>Description : <?= $experience['Experience']['description'];?></p>
        <?php else:?>
            <p>Pas de description</p>
        <?php endif; ?>
        <p>Du : <?= $this->Time->format($experience['Experience']['dateStart'], '%B %e, %Y');?> Au : <?= $this->Time->format($experience['Experience']['dateEnd'], '%B %e, %Y')?></p>
        <?php if($experience['Experience']['comment'] != ""): ?>
            <p>Avis : "<?= $experience['Experience']['comment'];?>"</p>
        <?php else:?>
            <p>Pas encore d'avis</p>
        <?php endif;?>
        
    </div>
<?php endforeach; ?>
    <?php if(empty($experiences)): ?>
        <p>Aucune expérience trouvée.</p>
    <?php endif; ?>
<?php else: ?>
        <p><?= $this->Html->link("Connecte-toi", array('controller'=>'users', 'action' => 'login')); ?> ou <?= $this->Html->link("inscris-toi", array('controller'=>'users', 'action' => 'signup')); ?> pour consulter les expériences</p>
<?php endif; ?>