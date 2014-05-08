<?php if(AuthComponent::user('id')) : ?>
    <?php foreach ($experiences as $experience): ?>
    <div class="well">
        <div class="row">
            <div class="col-sm-2 profile-info-search">
                <p><?= $this->Html->image('avatar.png', array('alt' => 'avatar','class' => 'avatar','id' => 'avatar_search','width' => '60px','onload' => "this.style.backgroundColor='#".$school_colors[$experience['User']['school_id']]."'"));?></p>
                <p><?= $this->Html->link($experience['User']['firstname'].' '.$experience['User']['lastname'],array('controller'=>'users', 'action' => 'profile', $experience['User']['id']));?></p>
                <p>Polytech <?= $school_names[$experience['User']['school_id']];?></p>
                <p><?= $departments[$experience['User']['department_id']];?></p>
            </div>
            <div class="col-sm-9">
                
                <?php echo $this->element('experience_info',array('experience'=>$experience)); ?>
                
            </div>
        </div>
    </div>
<?php endforeach; ?>
    <?php 
        //si le nombre de resultats est egale a la limite de resultat on affiche un bouton plus
        if(count($experiences) == $result_limit): ?>
        <p style="text-align: center">
            <a style="cursor: pointer" onclick="$(this).remove();get_experiences('get_experiences_search')">plus</a>
        </p>
    <?php endif;?>
    <?php //si aucune expérience n'a été trouvée
        if(empty($experiences) && $offset === '0'): ?>
            <p>Aucune expérience trouvée.</p>
    <?php endif; ?>
<?php else: ?>
        <p><?= $this->Html->link("Connecte-toi", array('controller'=>'users', 'action' => 'login')); ?> ou <?= $this->Html->link("inscris-toi", array('controller'=>'users', 'action' => 'signup')); ?> pour consulter les expériences</p>
<?php endif; ?>