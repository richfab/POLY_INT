<?php if(AuthComponent::user('id')) : ?>
    <?php foreach ($experiences as $experience): ?>
    <div class="well">
        <div class="row">
            <div class="col-sm-2 profile-info-search">
                <p><?= $this->Html->image('avatar.jpg', array('alt' => 'avatar'));?></p>
                <p><?= $this->Html->link($experience['User']['firstname'].' '.$experience['User']['lastname'],array('controller'=>'users', 'action' => 'profile', $experience['User']['id']));?></p>
                <p>Polytech <?= $experience['User']['School']['name'];?></p>
                <p><?= $experience['User']['Department']['name'];?></p>
            </div>
            <div class="col-sm-9">
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
        </div>
    </div>
<?php endforeach; ?>
    <?php 
        //si le nombre de resultats est egale a la limite de resultat on affiche un bouton plus
        if(count($experiences) == $result_limit): ?>
        <p style="text-align: center">
            <a onclick="this.remove();get_experiences_search()">plus</a>
        </p>
    <?php endif;?>
    <?php if(empty($experiences)): ?>
        <p>Aucune expérience trouvée.</p>
    <?php endif; ?>
<?php else: ?>
        <p><?= $this->Html->link("Connecte-toi", array('controller'=>'users', 'action' => 'login')); ?> ou <?= $this->Html->link("inscris-toi", array('controller'=>'users', 'action' => 'signup')); ?> pour consulter les expériences</p>
<?php endif; ?>