<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<?php foreach($activities as $activity):?>
    
    <?php if(key($activity) == 'Experience'): ?>

        <p>
            <span class="glyphicon glyphicon-globe"></span> <?= $activity['User']['firstname']; ?> <?= $activity['User']['lastname']; ?> a ajouté une expérience à <?= $activity['City']['name']; ?>, <?= $activity['City']['country_id']; ?>
        </p>
        
        <?php echo $this->element('experience_info',array('experience'=>$activity)); ?>

    <?php endif;?>
        
    <?php if(key($activity) == 'Photo'): ?>
        
        <p>
            <span class="glyphicon glyphicon-picture"></span> <?= $activity['Experience']['User']['firstname']; ?> <?= $activity['Experience']['User']['lastname']; ?> a ajouté des photos à <?= $activity['Experience']['City']['name']; ?>, <?= $activity['Experience']['City']['country_id']; ?>
        </p>
        <div class="panel-body photo_gallery" experience_id="<?= $activity['Experience']['id']; ?>">
                        
        </div>
        
    <?php endif;?>
        
    <?php if(key($activity) == 'Recommendation'): ?>

        <p>
            <span class="glyphicon glyphicon-comment"></span> <?= $activity['Experience']['User']['firstname']; ?> <?= $activity['Experience']['User']['lastname']; ?> a ajouté un bon plan à <?= $activity['Experience']['City']['name']; ?>, <?= $activity['Experience']['City']['country_id']; ?>
            <br/>
            <?php echo $this->element('recommendation_text',array('recommendation'=>$activity['Recommendation'])); ?>

        </p>

    <?php endif;?>
        
    <?php if(key($activity) == 'User'): ?>

    <p>
        <span class="glyphicon glyphicon-user"></span> <?= $activity['User']['firstname']; ?> <?= $activity['User']['lastname']; ?> a rejoint la communauté Polytech Abroad
    </p>

    <?php endif;?>
    
    <p>
        <time class="timeago" datetime="<?php echo $activity['Activity']['created']; ?>"><?php echo $activity['Activity']['created']; ?></time>
    </p>
        
    <p>
        ---------------------------------------------------------------
    </p>

<?php endforeach; ?>
    
<p style="text-align: center">
    <a style="cursor: pointer" onclick='get_activities(<?= $offset; ?>);$(this).remove();'>plus</a>
</p>

<?php
    echo $this->Html->script(array('jquery.blueimp-gallery.min.js')); // Inclut la librairie gallerie
?>

<script type="text/javascript">

$( function() {
    
    //pour les timeago
    $("time.timeago").timeago();

    //pour le chargement des galleries
    get_galleries('<?php echo $this->Html->url(array('controller' => 'photos', 'action' => 'get_photo_gallery'),true); ?>');


});
    
</script>