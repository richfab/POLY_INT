<?php if(AuthComponent::user('id')) : ?>
    <?php foreach ($experiences as $experience): ?>
    <a href="<?= $this->Html->url(array('controller'=>'users', 'action' => 'profile', $experience['User']['id'],'#' => $experience['Experience']['id']),true);?>">
        <div class="well well-experience-search">
            <div class="row">
                <div class="col-sm-2 profile-info-search">
                    <div><?= $this->Html->image('avatar.png', array('alt' => 'avatar','class' => 'avatar','id' => 'avatar_search','width' => '60px','onload' => "this.style.backgroundColor='#".$school_colors[$experience['User']['school_id']]."'"));?></div>
                    <div><?= $experience['User']['firstname'].' '.$experience['User']['lastname'];?></div>
                    <div>
                        <span class="help-block">
                            <?= $this->Html->image('picto/'.$experience['User']['department_id'].'.png',array('class' => 'department_logo_search', 'title' => $departments[$experience['User']['department_id']], 'data-toggle' => 'tooltip', 'data-placement' => 'bottom'));?> <small>Polytech <?= $school_names[$experience['User']['school_id']];?></small>
                        </span>
                    </div>
                </div>
                <div class="col-sm-10 experience-info">
                
                    <?php echo $this->element('experience_info',array('experience'=>$experience)); ?>
                
                </div>
            </div>
        </div>
    </a>
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

<script type="text/javascript">
    
    $( function() {
        //pour les tooltips
        if (!Modernizr.touch) {
            $('.department_logo_search').tooltip();
        }
    });
    
</script>