<?php if(AuthComponent::user('id')) : ?>
    <?php foreach ($recommendations as $recommendation): ?>
    <div class="well no-padding">
        <div class="row">
            <div class="col-sm-2 profile-info-search">
                <p><?php echo $this->element('recommendation_icon',array('recommendationtype_icon'=>$recommendationtype_icons[$recommendation['Recommendation']['recommendationtype_id']],'recommendationtype_name'=>$recommendationtype_names[$recommendation['Recommendation']['recommendationtype_id']])); ?></p>
                <p><?= $recommendation['Experience']['City']['name'];?>, <?= $countries[$recommendation['Experience']['City']['country_id']];?></p>
            </div>
            <div class="col-sm-9 profile-content-search">
                <?php echo $this->element('recommendation_text',array('recommendation'=>$recommendation['Recommendation'])); ?>
                <p><small>le <?= $this->Time->format($recommendation['Recommendation']['modified'], '%e %B %Y');?> par 
                <?= $this->Html->link($recommendation['Experience']['User']['firstname'].' '.$recommendation['Experience']['User']['lastname'],array('controller'=>'users', 'action' => 'profile', $recommendation['Experience']['User']['id']));?></small></p>
            </div>
        </div>
    </div>
<?php endforeach; ?>
    <?php 
        //si le nombre de resultats est egale a la limite de resultat on affiche un bouton plus
        if(count($recommendations) == $result_limit): ?>
        <p style="text-align: center">
            <a style="cursor: pointer" onclick="$(this).remove();get_recommendations()">plus</a>
        </p>
    <?php endif;?>
    <?php //si aucune recommendation n'a été trouvée
        if(empty($recommendation) && $offset === '0'): ?>
            <p>Aucune recommendation pour ces catégories.</p>
    <?php endif; ?>
<?php else: ?>
        <p><?= $this->Html->link("Connecte-toi", array('controller'=>'users', 'action' => 'login')); ?> ou <?= $this->Html->link("inscris-toi", array('controller'=>'users', 'action' => 'signup')); ?> pour consulter les bons plans</p>
<?php endif; ?>