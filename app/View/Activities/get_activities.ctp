<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>


<?php foreach($activities as $activity):?>
    
    <?php if($activity['Activity']['type'] == 'experience'): ?>
        <div class="activity activity-post">
            <div class="activity-profil-picture">
                <?php 
                    if(!empty($activity['Experience']['User']['avatar'])) {
                            echo $this->Image->resize($activity['Experience']['User']['avatar'],30,30,array('alt' => 'avatar','height' => '30px','onload' => "this.style.backgroundColor='#FFF'"));
                    } else {
                        echo $this->Html->image('avatar.png', array('height'=> '30px', 'alt' => 'avatar','onload' => "this.style.backgroundColor='#FFF'"));
                    }
                ?>
            </div>
            <div class="activity-subject">
                <span class="glyphicon glyphicon-globe"></span> <?= $this->Html->link($activity['User']['firstname'].' '.$activity['User']['lastname'],array('controller'=>'users', 'action' => 'profile', $activity['User']['id']));?> a ajouté une expérience
            </div>
            <div class="activity-content">
                 <?php echo $this->element('experience_info',array('experience'=>$activity)); ?>
            </div>
            <div class="activity-actions">
                <a href="#" class="pull-left"><i class="fa fa-users"></i> <?php echo count($activity['Activity']['people_around']); ?></a> 

                <a href="#" class="pull-right"><i class="fa fa-clock-o"></i> <time class="timeago" datetime="<?php echo $activity['Activity']['created']; ?>"><?php echo $activity['Activity']['created']; ?></time></a>
            </div>
        </div>

    <?php endif;?>
        
    <?php if($activity['Activity']['type'] == 'photo'): ?>
        
        <div class="activity activity-photo">
            <div class="activity-profil-picture">
                <?php 
                    if(!empty($activity['Experience']['User']['avatar'])) {
                            echo $this->Image->resize($activity['Experience']['User']['avatar'],30,30,array('alt' => 'avatar','height' => '30px','onload' => "this.style.backgroundColor='#FFF'"));
                    } else {
                        echo $this->Html->image('avatar.png', array('height'=> '30px', 'alt' => 'avatar','onload' => "this.style.backgroundColor='#FFF'"));
                    }
                ?>
            </div>
            <div class="activity-subject">
                <p><span class="glyphicon glyphicon-picture"></span> <?= $this->Html->link($activity['Experience']['User']['firstname'].' '.$activity['Experience']['User']['lastname'],array('controller'=>'users', 'action' => 'profile', $activity['Experience']['User']['id']));?> a ajouté des photos à <?= $activity['Experience']['City']['name']; ?>, <?= $countries[$activity['Experience']['City']['country_id']];?></p>
            </div>
            <div class="activity-content">
                 <div class="photo_gallery" experience_id="<?= $activity['Experience']['id']; ?>">
                        
                </div>
            </div>
            <div class="activity-actions">
                <a href="#" class="pull-left"><i class="fa fa-users"></i> <?php echo count($activity['Activity']['people_around']); ?></a> 

                <a href="#" class="pull-right"><i class="fa fa-clock-o"></i> <time class="timeago" datetime="<?php echo $activity['Activity']['created']; ?>"><?php echo $activity['Activity']['created']; ?></time></a>
            </div>
        </div>
        
    <?php endif;?>
        
    <?php if($activity['Activity']['type'] == 'recommendation'): ?>
        <div class="activity activity-recommendation">
            <div class="activity-profil-picture">
                <?php 
                    if(!empty($activity['Experience']['User']['avatar'])) {
                            echo $this->Image->resize($activity['Experience']['User']['avatar'],30,30,array('alt' => 'avatar','height' => '30px','onload' => "this.style.backgroundColor='#FFF'"));
                    } else {
                        echo $this->Html->image('avatar.png', array('height'=> '30px', 'alt' => 'avatar','onload' => "this.style.backgroundColor='#FFF'"));
                    }
                ?>
            </div>
            <div class="activity-subject">
                <span class="glyphicon glyphicon-comment"></span> <?= $this->Html->link($activity['Experience']['User']['firstname'].' '.$activity['Experience']['User']['lastname'],array('controller'=>'users', 'action' => 'profile', $activity['Experience']['User']['id']));?> a ajouté un bon plan à <?= $activity['Experience']['City']['name']; ?>, <?= $countries[$activity['Experience']['City']['country_id']];?>
            </div>
            <div class="activity-content">
                <ul class="icons-list">
                    <li>
                        <i class="li-icon"><?php echo $this->element('recommendation_icon',array('recommendationtype_icon'=>$activity['Recommendationtype']['icon'],'recommendationtype_name'=>$activity['Recommendationtype']['name'])); ?></i>
                        <?php echo $this->element('recommendation_text',array('recommendation'=>$activity['Recommendation'])); ?>
                    </li>
                </ul>
            </div>
            <div class="activity-actions">
                <a href="#" class="pull-left"><i class="fa fa-users"></i> <?php echo count($activity['Activity']['people_around']); ?></a> 

                <a href="#" class="pull-right"><i class="fa fa-clock-o"></i> <time class="timeago" datetime="<?php echo $activity['Activity']['created']; ?>"><?php echo $activity['Activity']['created']; ?></time></a>
            </div>
        </div>
    <?php endif;?>
        
    <?php if($activity['Activity']['type'] == 'user'): ?>
        <div class="activity activity-post">
            <div class="activity-profil-picture">
                <?php 
                    if(!empty($activity['Experience']['User']['avatar'])) {
                            echo $this->Image->resize($activity['Experience']['User']['avatar'],30,30,array('alt' => 'avatar','height' => '30px','onload' => "this.style.backgroundColor='#FFF'"));
                    } else {
                        echo $this->Html->image('avatar.png', array('height'=> '30px', 'alt' => 'avatar','onload' => "this.style.backgroundColor='#FFF'"));
                    }
                ?>
            </div>
            <div class="activity-subject">
                <span class="glyphicon glyphicon-user"></span> <?= $this->Html->link($activity['User']['firstname'].' '.$activity['User']['lastname'],array('controller'=>'users', 'action' => 'profile', $activity['User']['id']));?> a rejoint la communauté Polytech Abroad
            </div>
            <div class="activity-actions">
                <a href="#" class="pull-right"><i class="fa fa-clock-o"></i> <time class="timeago" datetime="<?php echo $activity['Activity']['created']; ?>"><?php echo $activity['Activity']['created']; ?></time></a>
            </div>
        </div>
    <?php endif;?>

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