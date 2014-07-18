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
            <span class="glyphicon glyphicon-globe"></span> <?= $activity['User']['firstname']; ?> <?= $activity['User']['lastname']; ?> a ajouté une expérience à <?= $activity['City']['name']; ?>, <?= $activity['City']['country_id']; ?> (<?= $activity['Experience']['created']; ?>)
        </p>

    <?php endif;?>
        
    <?php if(key($activity) == 'Photo'): ?>
        
        <p>
            <span class="glyphicon glyphicon-picture"></span> <?= $activity['Experience']['User']['firstname']; ?> <?= $activity['Experience']['User']['lastname']; ?> a ajouté des photos à <?= $activity['Experience']['City']['name']; ?>, <?= $activity['Experience']['City']['country_id']; ?> (<?= $activity['Experience']['created']; ?>)
        </p>
        
    <?php endif;?>
        
    <?php if(key($activity) == 'Recommendation'): ?>

        <p>
            <span class="glyphicon glyphicon-comment"></span> <?= $activity['Experience']['User']['firstname']; ?> <?= $activity['Experience']['User']['lastname']; ?> a ajouté un bon plan à <?= $activity['Experience']['City']['name']; ?>, <?= $activity['Experience']['City']['country_id']; ?> (<?= $activity['Experience']['created']; ?>)
        </p>

    <?php endif;?>
        
    <?php if(key($activity) == 'User'): ?>

    <p>
        <span class="glyphicon glyphicon-user"></span> <?= $activity['User']['firstname']; ?> <?= $activity['User']['lastname']; ?> a rejoint la communauté Polytech Abroad (<?= $activity['User']['created']; ?>)
    </p>

    <?php endif;?>
        
    <p>
        ---------------------------------------------------------------
    </p>

<?php endforeach; ?>
