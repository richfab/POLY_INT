<?php

/* 
 * Home page Polytech Abroad
 */
?>

<div id="slideshow" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <li data-target="#slideshow" data-slide-to="0" class="active"></li>
        <li data-target="#slideshow" data-slide-to="1"></li>
        <li data-target="#slideshow" data-slide-to="2"></li>
        <li data-target="#slideshow" data-slide-to="3"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" id="presentation">
        <div class="item active" style="background-image:url('./img/background3.jpg')"></div>
        <div class="item" style="background-image:url('./img/map.jpg')"></div>
        <div class="item" style="background-image:url('./img/boats.jpg')"></div>
        <div class="item" style="background-image:url('./img/houses.jpg')"></div>
    </div>
</div>

<div class="block-color hidden-xs">
    <div class="container">
        <h2><?= __("Polytech Abroad est un véritable passeport partagé entre tous les étudiants du réseau Polytech. C'est l'unique plate-forme qui permet de contacter rapidement n'importe quel étudiant de Polytech parti en stage ou en semestre à l'étranger.");?></h2>
    </div>
</div>

<div class="access">
    <?= $this->Html->link(
     __('poster une experience'),
     array('controller'=>'experiences', 'action'=>'info'),
     array('class'=>'btn btn-default btn-blue',"role"=>"button"));?>
    <?= $this->Html->link(
     __('explorer la carte'),
     array('controller'=>'experiences', 'action'=>'explore'),
     array('class'=>'btn btn-default btn-orange',"role"=>"button"));?>
</div>

<div class="container marketing">
    <h2 class="mini-marketing"><?= __('a quoi ça sert');?> <span class="wut">?</span></h2>
    <div class="row row-marketing">
        <div class="col-md-3">
            <?= $this->Html->image('5.png', array('alt' => 'geolocalisation'));?>
            <h2><?= __('Geolocalisation');?></h2>
            <p><?= __("Retrouve les étudiants de Polytech en stage, études ou voyage à l'étranger");?></p>
        </div>
        <div class="col-md-3">
            <?= $this->Html->image('8.png', array('alt' => 'temps'));?>
            <h2><?= __('Depuis 2013');?></h2>
            <p><?= __('Remonte le temps et découvre où sont partis les étudiants avant toi');?></p>
        </div>
        <div class="col-md-3">
            <?= $this->Html->image('14.png', array('alt' => 'retour experience'));?>
            <h2><?= __("Retour d'experience");?></h2>
            <p><?= __('Consulte et partage des bons plans sur des destinations du monde entier');?></p>
        </div>
        <div class="col-md-3">
            <?= $this->Html->image('11.png', array('alt' => 'social'));?>
            <h2><?= __('Social');?></h2>
            <p><?= __('Contacte facilement les étudiants déjà présent dans un pays');?></p>
        </div>
    </div>
</div>

<div class="access">

</div>